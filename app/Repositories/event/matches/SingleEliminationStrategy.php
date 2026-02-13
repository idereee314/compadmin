<?php
namespace event\matches;

use event\EventBrackets;
use event\EventMatches;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Single Elimination tournament format
 * Loser is eliminated from tournament
 */
class SingleEliminationStrategy implements TournamentEliminationStrategy {

    /**
     * Generate bracket structure for single elimination
     */
    public function generateBracket($eventId, $participants, $config)
    {
        $validation = $this->validateConfig($config);
        if (!$validation['valid']) {
            Log::error('Invalid configuration for Single Elimination', $validation['errors']);
            return [];
        }

        $participantCount = count($participants);
        if ($participantCount < 2) {
            Log::error('Minimum 2 participants required for Single Elimination');
            return [];
        }

        $bracketStructure = [];
        $rounds = $this->calculateTotalRounds($participantCount);

        // Generate bracket positions
        $positions = $this->calculateBracketPositions($participantCount);

        foreach ($participants as $index => $participant) {
            $bracketEntry = [
                'event_id' => $eventId,
                'participant_id' => $participant['id'] ?? $participant,
                'position' => $positions[$index] ?? $index + 1,
                'round' => 1,
                'status' => 'P',
                'total_rounds' => $rounds,
            ];
            $bracketStructure[] = $bracketEntry;
        }

        return $bracketStructure;
    }

    /**
     * Generate matches for current round
     */
    public function generateRoundMatches($eventId, $roundNumber, $participantRegistrations = [])
    {
        $matches = [];

        // If first round, pair initial participants
        if ($roundNumber === 1) {
            return $this->generateFirstRoundMatches($eventId, $participantRegistrations);
        }

        // For subsequent rounds in all-rounds-at-once generation,
        // we create matches with empty participant IDs and link via previes_mate_id1/id2
        // Winners will be filled in as matches are completed
        $matchesInFirstRound = count($participantRegistrations) >= 2 ? floor(count($participantRegistrations) / 2) : 1;
        $matchesNeeded = floor($matchesInFirstRound / 2);

        for ($i = 0; $i < $matchesNeeded; $i++) {
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,  // Will be filled when previous round winners are determined
                'reg_two_id' => null,
                'order_no' => $roundNumber * 100 + $i + 1,
                'status' => 'P',
                'is_double_loser' => 0,
            ];
        }

        return $matches;
    }

    /**
     * Generate first round matches from initial participants
     */
    private function generateFirstRoundMatches($eventId, $participantRegistrations)
    {
        $matches = [];
        $matchOrder = 1;

        // Use provided registrations, or fetch from database if empty
        if (empty($participantRegistrations)) {
            $participantRegistrations = \DB::table('uq_event_registration')
                ->where('event_id', $eventId)
                ->get(['id'])
                ->pluck('id')
                ->toArray();
        }

        for ($i = 0; $i < count($participantRegistrations); $i += 2) {
            if (isset($participantRegistrations[$i + 1])) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => $participantRegistrations[$i],
                    'reg_two_id' => $participantRegistrations[$i + 1],
                    'previes_mate_id1' => null,
                    'previes_mate_id2' => null,
                    'order_no' => $matchOrder++,
                    'status' => 'P',
                    'is_double_loser' => 0,
                ];
            }
        }

        return $matches;
    }

    /**
     * Process match result
     */
    public function processMatchResult($matchId, $winnerId, $matchData)
    {
        $match = EventMatches::find($matchId);
        if (!$match) {
            return false;
        }

        $match->reg_win_id = $winnerId;
        $match->status = 'completed';
        $match->end_time = Carbon::now();
        $match->save();

        Log::info('Single Elimination match completed', [
            'match_id' => $matchId,
            'winner_id' => $winnerId,
        ]);

        return true;
    }

    /**
     * Get next round matches
     * Note: With all-rounds-at-once approach, all matches are pre-generated during initialization
     * This method is kept for backward compatibility but returns empty array
     */
    public function getNextRoundMatches($bracketId, $currentRound)
    {
        // All rounds are pre-generated during tournament initialization
        // No need to generate matches dynamically
        return [];
    }

    /**
     * Check if round is complete
     */
    public function isRoundComplete($bracketId, $roundNumber)
    {
        $totalMatches = EventMatches::where('bracket_id', $bracketId)
            ->where('round', $roundNumber)
            ->count();

        $completedMatches = EventMatches::where('bracket_id', $bracketId)
            ->where('round', $roundNumber)
            ->where('status', 'completed')
            ->count();

        return $totalMatches > 0 && $totalMatches === $completedMatches;
    }

    /**
     * Get final match
     */
    public function getFinalMatch($bracketId)
    {
        $totalRounds = EventMatches::where('bracket_id', $bracketId)
            ->max('round');

        return EventMatches::where('bracket_id', $bracketId)
            ->where('round', $totalRounds)
            ->first();
    }

    /**
     * Get tournament standings
     */
    public function getTournamentStandings($bracketId)
    {
        return EventMatches::where('bracket_id', $bracketId)
            ->where('status', 'completed')
            ->orderBy('round', 'desc')
            ->get(['reg_win_id', 'round', 'match_order'])
            ->toArray();
    }

    /**
     * Validate configuration
     */
    public function validateConfig($config)
    {
        $errors = [];

        if (!isset($config['event_id'])) {
            $errors['event_id'] = 'Event ID is required';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get elimination type
     */
    public function getEliminationType()
    {
        return 'single_elimination';
    }

    /**
     * Calculate total rounds needed
     */
    public function calculateTotalRounds($participantCount)
    {
        if ($participantCount <= 1) {
            return 0;
        }
        return ceil(log($participantCount, 2));
    }

    /**
     * Calculate bracket positions (seeding)
     */
    private function calculateBracketPositions($participantCount)
    {
        $positions = [];
        $power = 1;
        while ($power < $participantCount) {
            $power *= 2;
        }

        for ($i = 0; $i < $participantCount; $i++) {
            $positions[] = $i + 1;
        }

        return $positions;
    }

    /**
     * Compute previous-match references for single elimination
     * Handles standard 2*idx mapping + special case for bronze/medal matches in final round
     */
    public function computePreviousReferences($roundMatchesData)
    {
        $roundNumbers = array_keys($roundMatchesData);
        sort($roundNumbers);
        $totalRounds = end($roundNumbers);

        foreach ($roundNumbers as $roundNumber) {
            if ($roundNumber <= 1) {
                // first round has no previous refs
                continue;
            }

            if (!isset($roundMatchesData[$roundNumber - 1]) || !isset($roundMatchesData[$roundNumber])) {
                continue;
            }

            $previous = $roundMatchesData[$roundNumber - 1];
            $current = &$roundMatchesData[$roundNumber];

            foreach ($current as $idx => &$matchData) {
                // Prev matches indices in previous round are 2*idx and 2*idx+1
                $p1Index = $idx * 2;
                $p2Index = $idx * 2 + 1;

                // If the standard mapping doesn't exist (e.g. bronze/medal matches appended
                // to the final round), fall back to pairing the losers from previous round.
                if (!isset($previous[$p1Index]) || !isset($previous[$p2Index])) {
                    if ($roundNumber === $totalRounds && count($previous) >= 2) {
                        // Map to first and last previous match (semifinal winners/losers)
                        $p1Index = 0;
                        $p2Index = count($previous) - 1;
                    }
                }

                $refs = [];
                if (isset($previous[$p1Index])) {
                    $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $p1Index];
                }
                if (isset($previous[$p2Index])) {
                    $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $p2Index];
                }

                if (!empty($refs)) {
                    $matchData['prev_refs'] = $refs;
                }
            }
            unset($matchData);
            unset($current);
        }

        return $roundMatchesData;
    }
}
