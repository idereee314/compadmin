<?php
namespace event\matches;

use event\EventBrackets;
use event\EventMatches;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Tournament Match Management Service
 * Orchestrates match generation and result processing across different elimination formats
 */
class TournamentMatchService {

    /**
     * Tournament elimination strategy factory
     */
    protected $strategyFactory;

    /**
     * Current tournament strategy
     */
    protected $strategy;

    /**
     * Constructor
     */
    public function __construct(TournamentEliminationStrategyFactory $factory = null)
    {
        $this->strategyFactory = $factory ?? new TournamentEliminationStrategyFactory();
    }

    /**
     * Initialize tournament with selected elimination format
     * 
     * @param int $eventId
     * @param string $eliminationType - Type of tournament (single_elimination, double_elimination, round_robin)
     * @param array $participants - List of registered participants/registrations
     * @param array $config - Tournament configuration
     * @return array
     */
    public function initializeTournament($eventId, $eliminationType, $participants, $matchBracket, $config = [])
    {
        $this->strategy = $this->strategyFactory->createStrategy($eliminationType);
        $participantCount = count($participants);
        
        if (!$this->strategy) {
            Log::error('Failed to initialize tournament - invalid elimination type', [
                'event_id' => $eventId,
                'elimination_type' => $eliminationType,
            ]);
            return ['success' => false, 'error' => 'Invalid elimination type'];
        }

        try {
            DB::beginTransaction();

            $config['event_id'] = $eventId;
            $validation = $this->strategy->validateConfig($config);
            
            if (!$validation['valid']) {
                DB::rollBack();
                return [
                    'success' => false,
                    'error' => 'Configuration validation failed',
                    'errors' => $validation['errors'],
                ];
            }

            // Extract participant registration IDs
            $participantRegistrations = [];
            foreach ($participants as $participant) {
                if (is_array($participant)) {
                    $participantRegistrations[] = $participant['id'] ?? $participant;
                } else {
                    $participantRegistrations[] = $participant;
                }
            }

            // Generate all tournament rounds data (not persisted yet)
            $roundMatchesData = $this->generateAllRounds($eventId, $eliminationType, count($participants), $participantRegistrations);

            // Compute previous-match references using strategy-specific logic
            $roundMatchesData = $this->strategy->computePreviousReferences($roundMatchesData);

            // Persist all generated matches and collect saved models for linking
            $totalMatches = 0;
            $savedRoundMatches = [];
            // Save round-by-round so previous round IDs are available when saving next round
            ksort($roundMatchesData);
            foreach ($roundMatchesData as $roundNumber => $matches) {
                $savedRoundMatches[$roundNumber] = [];
                foreach ($matches as $matchIndex => $matchData) {
                    $match = new EventMatches();
                    $match->event_id = $eventId;
                    $match->bracket_id = $matchBracket->id;
                    $match->status = $matchData['status'] ?? 'P';

                    if (isset($matchData['reg_one_id'])) {
                        $match->reg_one_id = $matchData['reg_one_id'];
                    }
                    if (isset($matchData['reg_two_id'])) {
                        $match->reg_two_id = $matchData['reg_two_id'];
                    }
                    // Default order (use pre-generated value if present)
                    if (isset($matchData['order_no'])) {
                        $match->order_no = $matchData['order_no'];
                    }
                    if (isset($matchData['bracket_id'])) {
                        $match->bracket_id = $matchData['bracket_id'];
                    }
                    if (isset($matchData['is_double_loser'])) {
                        $match->is_double_loser = $matchData['is_double_loser'];
                    }

                    // If previous refs were computed, set actual prev match IDs using saved models
                    if (!empty($matchData['prev_refs']) && is_array($matchData['prev_refs'])) {
                        // prev_refs expected as ['p1' => ['round'=>r,'index'=>i], 'p2' => [...]]
                        if (isset($matchData['prev_refs']['p1'])) {
                            $p1 = $matchData['prev_refs']['p1'];
                            if (isset($savedRoundMatches[$p1['round']][$p1['index']])) {
                                $match->previes_mate_id1 = $savedRoundMatches[$p1['round']][$p1['index']]->id;
                            }
                        }
                        if (isset($matchData['prev_refs']['p2'])) {
                            $p2 = $matchData['prev_refs']['p2'];
                            if (isset($savedRoundMatches[$p2['round']][$p2['index']])) {
                                $match->previes_mate_id2 = $savedRoundMatches[$p2['round']][$p2['index']]->id;
                            }
                        }
                    }

                    // Persist match so it has an ID for later rounds
                    $match->save();
                    $savedRoundMatches[$roundNumber][] = $match;
                    $totalMatches++;
                }
            }
            Log::info('Tournament initialized successfully', [
                'roundMatchesData' => $roundMatchesData,
            ]);
            DB::commit();

            $totalRounds = $this->strategy->calculateTotalRounds(count($participants));

            return [
                'success' => true,
                'event_id' => $eventId,
                'elimination_type' => $eliminationType,
                'total_rounds' => $totalRounds,
                'total_matches' => $totalMatches,
                'participant_count' => count($participants),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error initializing tournament', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate all tournament rounds at once
     * 
     * @param int $eventId
     * @param string $eliminationType
     * @param int $participantCount
     * @param array $participantRegistrations - Registration IDs of participants
     * @return int - Total number of matches created
     */
    protected function generateAllRounds($eventId, $eliminationType, $participantCount, $participantRegistrations = [])
    {
        // Ensure total rounds is integer to avoid strict comparison issues
        $totalRounds = (int) $this->strategy->calculateTotalRounds($participantCount);
        $roundMatches = []; // Store match data arrays by round

        // Build all matches data for every round, but do not persist here
        for ($roundNumber = 1; $roundNumber <= $totalRounds; $roundNumber++) {
            try {
                $matches = $this->strategy->generateRoundMatches($eventId, $roundNumber, $participantRegistrations);
                $roundMatches[$roundNumber] = [];

                foreach ($matches as $idx => $matchData) {
                    // Ensure required metadata is present
                    $matchData['status'] = $matchData['status'] ?? 'P';

                    // Gold (9999) and Bronze (9997/9996/9998) order_no values
                    // are set directly by the strategy in generateRoundMatches

                    $roundMatches[$roundNumber][] = $matchData;
                }

            } catch (\Exception $e) {
                Log::error('Error generating round matches (data)', [
                    'event_id' => $eventId,
                    'round' => $roundNumber,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        // Return structured match data grouped by round for caller to persist
        return $roundMatches;
    }

    /**
     * Link previous matches for winner advancement in subsequent rounds
     * 
     * @param int $eventId
     * @param array $roundMatches - Matches organized by round
     * @param string $eliminationType - Type of tournament
     */
    protected function linkPreviousMatches($eventId, $roundMatches, $eliminationType)
    {
        // For elimination tournaments, link previous matches
        if (in_array($eliminationType, ['single_elimination', 'double_elimination'])) {
            try {
                for ($roundNumber = 2; $roundNumber < count($roundMatches) + 1; $roundNumber++) {
                    if (!isset($roundMatches[$roundNumber])) {
                        continue;
                    }

                    $currentRoundMatches = $roundMatches[$roundNumber];
                    $previousRoundMatches = $roundMatches[$roundNumber - 1];

                    // Link matches: each match in current round comes from 2 matches in previous round
                    foreach ($currentRoundMatches as $matchIndex => $currentMatch) {
                        $prevMatch1Index = $matchIndex * 2;
                        $prevMatch2Index = $matchIndex * 2 + 1;

                        if (isset($previousRoundMatches[$prevMatch1Index])) {
                            $currentMatch->previes_mate_id1 = $previousRoundMatches[$prevMatch1Index]->id;
                        }

                        if (isset($previousRoundMatches[$prevMatch2Index])) {
                            $currentMatch->previes_mate_id2 = $previousRoundMatches[$prevMatch2Index]->id;
                        }

                        // $currentMatch->save();
                    }
                }
            } catch (Exception $e) {
                Log::error('Error linking previous matches', [
                    'event_id' => $eventId,
                    'error' => $e->getMessage(),
                ]);
                // Don't throw - linking is optional for functionality
            }
        }
    }

    /**
     * Record match result and advance winner
     * 
     * @param int $matchId
     * @param int $winnerId
     * @return array
     */
    public function recordMatchResult($matchId, $winnerId)
    {
        $match = EventMatches::find($matchId);
        
        if (!$match) {
            return ['success' => false, 'error' => 'Match not found'];
        }

        try {
            DB::beginTransaction();

            // Update match with winner
            $match->reg_win_id = $winnerId;
            $match->status = 'completed';
            $match->save();

            // All rounds are pre-generated during tournament initialization
            // No need to generate next round dynamically
            // Just update match result and let the system proceed

            DB::commit();

            return [
                'success' => true,
                'match_id' => $matchId,
                'winner_id' => $winnerId,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error recording match result', [
                'match_id' => $matchId,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate next round matches
     * Note: With all-rounds-at-once approach, all matches are pre-generated during initialization
     * This method is kept for backward compatibility but is no longer needed
     */
    protected function generateNextRound($bracketId, $roundNumber)
    {
        return true;
    }

    /**
     * Get tournament standings
     * 
     * @param int $eventId
     * @param string $eliminationType - Tournament elimination type
     * @return array
     */
    public function getTournamentStandings($eventId)
    {
        $matches = EventMatches::where('event_id', $eventId)
            ->where('status', 'completed')
            ->get();

        if ($matches->isEmpty()) {
            return [];
        }

        // Build standings from match results
        $standings = [];
        foreach ($matches as $match) {
            if ($match->reg_win_id) {
                if (!isset($standings[$match->reg_win_id])) {
                    $standings[$match->reg_win_id] = ['wins' => 0, 'losses' => 0, 'points' => 0];
                }
                $standings[$match->reg_win_id]['wins']++;
                $standings[$match->reg_win_id]['points'] += 3;
            }

            // Record loss for loser
            if ($match->reg_one_id && $match->reg_one_id !== $match->reg_win_id) {
                if (!isset($standings[$match->reg_one_id])) {
                    $standings[$match->reg_one_id] = ['wins' => 0, 'losses' => 0, 'points' => 0];
                }
                $standings[$match->reg_one_id]['losses']++;
            }
            if ($match->reg_two_id && $match->reg_two_id !== $match->reg_win_id) {
                if (!isset($standings[$match->reg_two_id])) {
                    $standings[$match->reg_two_id] = ['wins' => 0, 'losses' => 0, 'points' => 0];
                }
                $standings[$match->reg_two_id]['losses']++;
            }
        }

        // Sort by points descending
        usort($standings, function($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $standings;
    }

    /**
     * Get tournament final match/winner
     * 
     * @param int $eventId
     * @param string $eliminationType - Tournament elimination type
     * @return array|null
     */
    public function getTournamentFinal($eventId)
    {
        // Get the final match (last round with most matches)
        $finalMatch = EventMatches::where('event_id', $eventId)
            ->where('status', 'completed')
            ->orderBy('id', 'desc')
            ->first();

        return $finalMatch ? $finalMatch->toArray() : null;
    }

    /**
     * Get available elimination types
     * 
     * @return array
     */
    public function getAvailableEliminationTypes()
    {
        return $this->strategyFactory->getAvailableStrategies();
    }
}
