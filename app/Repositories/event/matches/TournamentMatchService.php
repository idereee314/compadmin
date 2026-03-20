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
                    if (isset($matchData['reg_win_id'])) {
                        $match->reg_win_id = $matchData['reg_win_id'];
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
            // Auto-advance BYE winners to their next-round matches
            $this->autoAdvanceByeWinners($savedRoundMatches);

            // Reorder all matches for this bracket so order_no reflects
            // the actual play sequence, respecting round dependencies and
            // maximising rest breaks for players.
            $this->reorderMatchesForBracket($matchBracket->id);

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
     * Auto-advance BYE winners to their next-round matches.
     *
     * A BYE match has one player (reg_one_id) and no opponent (reg_two_id = null),
     * with status 'C' and reg_win_id set. The winner is placed into the appropriate
     * slot of the next-round winners bracket match via previes_mate_id1/previes_mate_id2.
     *
     * For losers bracket matches linked to a BYE feeder, the "loser" is null
     * (no real opponent lost), so nothing is advanced there.
     */
    private function autoAdvanceByeWinners($savedRoundMatches)
    {
        foreach ($savedRoundMatches as $roundNumber => $matches) {
            foreach ($matches as $match) {
                if ($match->status !== 'C' || !$match->reg_win_id) {
                    continue;
                }

                // Find next-round matches fed by this completed match
                $nextMatches = EventMatches::where(function ($q) use ($match) {
                    $q->where('previes_mate_id1', $match->id)
                      ->orWhere('previes_mate_id2', $match->id);
                })->get();

                foreach ($nextMatches as $nextMatch) {
                    $changed = false;

                    // Winners bracket: advance the winner
                    if (!$nextMatch->is_double_loser) {
                        if ($nextMatch->previes_mate_id1 == $match->id && !$nextMatch->reg_one_id) {
                            $nextMatch->reg_one_id = $match->reg_win_id;
                            $changed = true;
                        }
                        if ($nextMatch->previes_mate_id2 == $match->id && !$nextMatch->reg_two_id) {
                            $nextMatch->reg_two_id = $match->reg_win_id;
                            $changed = true;
                        }
                    }
                    // Losers bracket: would advance the loser, but BYE matches
                    // have no loser (reg_two_id is null), so nothing to advance

                    if ($changed) {
                        $nextMatch->save();
                    }
                }
            }
        }
    }

    /**
     * Reorder all matches within a single bracket so that display_order
     * reflects the actual play sequence on the mat.
     *
     * IMPORTANT: This does NOT change order_no. The original order_no
     * values (1-4, 201-202, 2201-2202, 9996-9999) are preserved because
     * determineRound() and other logic depend on them to identify which
     * round/bracket a match belongs to. Instead, a new `display_order`
     * field is set on each match, representing the 1-based play sequence.
     *
     * The algorithm:
     *   1. Fetch all matches for the bracket, ordered by current order_no.
     *   2. Build a dependency graph from previes_mate_id1 / previes_mate_id2.
     *   3. Separate matches into layers:
     *      a) "BYE" matches: already completed (status='C'), unlock dependents
     *      b) "ready" matches: all dependencies satisfied
     *      c) "medal" matches: order_no >= 9996 (bronze, gold — always last)
     *   4. Use a greedy scheduler that picks the next playable match with
     *      the best rest break for its players (same algorithm as MatchScheduler).
     *   5. Assign sequential display_order (1, 2, 3 …) to non-medal matches.
     *      Medal matches get display_order after all regular matches.
     *
     * This ensures that:
     *   - Winners and losers bracket matches are interleaved optimally
     *   - Players get maximum rest between consecutive fights
     *   - Round dependencies are always respected
     *   - Medal/final matches are played last
     *   - order_no is preserved for determineRound() compatibility
     */
    protected function reorderMatchesForBracket($bracketId)
    {
        $allMatches = EventMatches::where('bracket_id', $bracketId)
            ->orderBy('order_no', 'asc')
            ->get();

        if ($allMatches->count() <= 1) {
            if ($allMatches->count() === 1) {
                $match = $allMatches->first();
                $match->display_order = 1;
                $match->save();
            }
            return;
        }

        // Index matches by ID for quick lookup
        $matchById = [];
        foreach ($allMatches as $match) {
            $matchById[$match->id] = $match;
        }

        // Separate medal matches (order_no >= 9996) — these are always played last
        $medalMatches = [];
        $regularMatches = [];
        foreach ($allMatches as $match) {
            if ($match->order_no >= 9996) {
                $medalMatches[] = $match;
            } else {
                $regularMatches[] = $match;
            }
        }

        // Build dependency sets: matchId => [feeder match IDs that must finish first]
        $dependencies = [];
        foreach ($regularMatches as $match) {
            $deps = [];
            if ($match->previes_mate_id1 && isset($matchById[$match->previes_mate_id1])) {
                $deps[] = $match->previes_mate_id1;
            }
            if ($match->previes_mate_id2 && isset($matchById[$match->previes_mate_id2])) {
                $deps[] = $match->previes_mate_id2;
            }
            $dependencies[$match->id] = $deps;
        }

        // Track scheduling state
        $scheduled = [];    // ordered list of match objects (play sequence)
        $doneIds = [];      // set of match IDs that are "done"
        $lastSlot = [];     // playerID => last slot played (for rest-break calc)

        // Pre-mark already-completed (BYE) matches as done
        // They don't occupy a play slot but unlock their dependents
        $byeMatchIds = [];
        foreach ($regularMatches as $match) {
            if ($match->status === 'C') {
                $byeMatchIds[$match->id] = true;
                if ($match->reg_one_id) {
                    $lastSlot[$match->reg_one_id] = 0;
                }
            }
        }

        // Greedy scheduling loop
        $slot = 1;
        $maxIterations = count($regularMatches) * count($regularMatches) + 10;
        $iteration = 0;

        while (count($scheduled) + count($byeMatchIds) < count($regularMatches)) {
            if (++$iteration > $maxIterations) {
                Log::warning('reorderMatchesForBracket: max iterations reached', [
                    'bracket_id' => $bracketId,
                    'scheduled' => count($scheduled),
                    'total' => count($regularMatches),
                ]);
                break;
            }

            // Find all "ready" matches: not yet done, all dependencies satisfied
            $candidates = [];
            foreach ($regularMatches as $match) {
                if (isset($doneIds[$match->id]) || isset($byeMatchIds[$match->id])) {
                    continue;
                }
                $ready = true;
                foreach ($dependencies[$match->id] as $depId) {
                    if (!isset($doneIds[$depId]) && !isset($byeMatchIds[$depId])) {
                        $ready = false;
                        break;
                    }
                }
                if ($ready) {
                    $candidates[] = $match;
                }
            }

            if (empty($candidates)) {
                // Fallback: force-schedule remaining (shouldn't happen normally)
                foreach ($regularMatches as $match) {
                    if (!isset($doneIds[$match->id]) && !isset($byeMatchIds[$match->id])) {
                        $candidates[] = $match;
                    }
                }
                if (empty($candidates)) {
                    break;
                }
            }

            // Pick the candidate with best minimum rest for its players
            $bestMatch = null;
            $bestMinWait = -1;

            foreach ($candidates as $match) {
                $p1 = $match->reg_one_id;
                $p2 = $match->reg_two_id;

                if ($p1 === null && $p2 === null) {
                    // Unknown players (later round) — defer in favour of known-player matches
                    $minWait = PHP_INT_MAX - 1;
                } else {
                    $wait1 = ($p1 !== null) ? ($slot - ($lastSlot[$p1] ?? 0)) : PHP_INT_MAX;
                    $wait2 = ($p2 !== null) ? ($slot - ($lastSlot[$p2] ?? 0)) : PHP_INT_MAX;
                    $minWait = min($wait1, $wait2);
                }

                if ($minWait > $bestMinWait) {
                    $bestMinWait = $minWait;
                    $bestMatch = $match;
                }
            }

            $scheduled[] = $bestMatch;
            $doneIds[$bestMatch->id] = true;

            if ($bestMatch->reg_one_id) {
                $lastSlot[$bestMatch->reg_one_id] = $slot;
            }
            if ($bestMatch->reg_two_id) {
                $lastSlot[$bestMatch->reg_two_id] = $slot;
            }
            $slot++;
        }

        // Assign sequential display_order to scheduled regular matches
        $displayOrder = 1;
        foreach ($scheduled as $match) {
            $match->display_order = $displayOrder++;
            $match->save();
        }

        // BYE matches get display_order = 0 (not played, but still in bracket)
        foreach ($regularMatches as $match) {
            if (isset($byeMatchIds[$match->id])) {
                $match->display_order = 0;
                $match->save();
            }
        }

        // Medal matches: maintain their relative order (9996 < 9997 < 9998 < 9999)
        // and assign display_order after all regular matches
        usort($medalMatches, function ($a, $b) {
            return $a->order_no <=> $b->order_no;
        });
        foreach ($medalMatches as $match) {
            $match->display_order = $displayOrder++;
            $match->save();
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
