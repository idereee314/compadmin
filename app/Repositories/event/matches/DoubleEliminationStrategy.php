<?php
namespace event\matches;

use event\EventBrackets;
use event\EventMatches;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DoubleEliminationStrategy implements TournamentEliminationStrategy {

    const WINNERS_BRACKET = 'winners';
    const LOSERS_BRACKET = 'losers';

    /**
     * Generate bracket structure for double elimination with repechage
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
     * Calculate losers bracket matches for a given round
     * Used to determine how many losers-bracket matches exist in previous rounds
     */
    private function calculateLosersMatchesForRound($roundNumber, $firstRoundMatches)
    {
        if ($roundNumber === 2) {
            // L1: First losers bracket from W1 losers
            return ceil($firstRoundMatches / 2);
        }
        
        if ($roundNumber >= 3) {
            // L2+: Combine W-losers with L-survivors
            $prevLMatches = $this->calculateLosersMatchesForRound($roundNumber - 1, $firstRoundMatches);
            $currentWMatches = floor($firstRoundMatches / pow(2, $roundNumber - 1));
            return ceil(($currentWMatches + $prevLMatches) / 2);
        }
        
        return 0;
    }

    public function generateRoundMatches($eventId, $roundNumber, $participantRegistrations = [])
    {
        $matches = [];

        // Round 1: Winners bracket only, same as single elimination
        if ($roundNumber === 1) {
            return $this->generateFirstRoundMatches($eventId, $participantRegistrations);
        }

        // Rounds 2+: Winners bracket + Losers bracket
        // Winners bracket follows single elimination pattern
        $matchesInFirstRound = count($participantRegistrations) >= 2 ? floor(count($participantRegistrations) / 2) : 1;
        $winnersBracketMatches = floor($matchesInFirstRound / pow(2, $roundNumber - 1));

        // Winners bracket matches (identical to single elimination)
        for ($i = 0; $i < $winnersBracketMatches; $i++) {
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => $roundNumber * 100 + $i + 1,
                'status' => 'P',
                'is_double_loser' => 0,  // Winners bracket
            ];
        }

        // Losers bracket matches (parallel to winners bracket, keeps halving)
        if ($roundNumber === 2) {
            // L1: First losers bracket round - losers from W1 paired together
            $l1Matches = ceil($matchesInFirstRound / 2);
            for ($i = 0; $i < $l1Matches; $i++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => null,
                    'reg_two_id' => null,
                    'order_no' => 2000 + $roundNumber * 100 + $i + 1,
                    'status' => 'P',
                    'is_double_loser' => 1,  // Losers bracket (L1)
                ];
            }
        } else if ($roundNumber >= 3) {
            // L2+: Losers bracket combines current W-round losers with previous L-round survivors
            // Calculate previous L-bracket matches using helper
            $prevLosersMatches = $this->calculateLosersMatchesForRound($roundNumber - 1, $matchesInFirstRound);
            // Current W-bracket losers (each match produces 1 loser)
            $currentWLosers = $winnersBracketMatches;
            // New L-bracket matches: (W-losers + L-survivors) / 2
            $lnMatches = ceil(($currentWLosers + $prevLosersMatches) / 2);
            
            if ($lnMatches > 0) {
                // Check if this is the final losers bracket round (will become bronze matches)
                // Final losers round always has exactly 2 matches
                $isFinalLosersRound = ($lnMatches === 2);
                
                for ($i = 0; $i < $lnMatches; $i++) {
                    // If final L round with 2 matches, mark as bronze (order_no 9997, 9996)
                    // Non-final losers rounds get 2000 offset so they sort after all winners bracket matches
                    $orderNo = $isFinalLosersRound ? (9997 - $i) : (2000 + ($roundNumber * 100) + $i + 1);
                    
                    $matches[] = [
                        'event_id' => $eventId,
                        'reg_one_id' => null,
                        'reg_two_id' => null,
                        'order_no' => $orderNo,
                        'status' => 'P',
                        'is_double_loser' => 1,  // Losers bracket (L2+) / Bronze
                    ];
                }
            }
        }

        return $matches;
    }

    private function generateFirstRoundMatches($eventId, $participantRegistrations)
    {
        $matches = [];
        $matchOrder = 1;

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

    public function getNextRoundMatches($bracketId, $currentRound)
    {
        return [];
    }

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

    public function getFinalMatch($bracketId)
    {
        $totalRounds = EventMatches::where('bracket_id', $bracketId)
            ->max('round');

        return EventMatches::where('bracket_id', $bracketId)
            ->where('round', $totalRounds)
            ->first();
    }

    public function getTournamentStandings($bracketId)
    {
        return EventMatches::where('bracket_id', $bracketId)
            ->where('status', 'completed')
            ->orderBy('round', 'desc')
            ->get(['reg_win_id', 'round', 'match_order'])
            ->toArray();
    }

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

    public function getEliminationType()
    {
        return 'double_elimination';
    }

    public function calculateTotalRounds($participantCount)
    {
        if ($participantCount <= 1) {
            return 0;
        }
        // Double elimination winners bracket = same as single elimination rounds
        // For 8: ceil(log2(8)) = 3 rounds
        // Losers bracket runs parallel (L1 in round 2, L2 in round 3, etc.)
        return ceil(log($participantCount, 2));
    }

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
     * Compute previous-match references for double elimination
     * 
     * Structure: Repechage mirrors & interleaves with winners bracket
     * 
     * Example 32-person tournament:
     * W1: 16 matches → 16 winners, 16 losers
     * L1: 8 matches (16 W1-losers paired) → 8 L1-winners
     * 
     * W2: 8 matches (16 W1-winners) → 8 winners, 8 losers
     * L2: 8 matches (8 W2-losers vs 8 L1-winners) → 8 L2-winners
     * 
     * W3: 4 matches (8 W2-winners) → 4 winners, 4 losers
     * L3: 4 matches (4 W3-losers vs 4 L2-winners) → 4 L3-winners
     * 
     * W4: 2 matches (4 W3-winners, semifinals) → 2 winners, 2 losers
     * L4: 2 bronze matches (2 W4-losers vs 2 L3-winners)
     * 
     * W5: 1 match (final) → Gold
     */
    public function computePreviousReferences($roundMatchesData)
    {
        $roundNumbers = array_keys($roundMatchesData);
        sort($roundNumbers);
        
        foreach ($roundNumbers as $roundNumber) {
            if ($roundNumber <= 1) {
                continue;
            }

            if (!isset($roundMatchesData[$roundNumber - 1]) || !isset($roundMatchesData[$roundNumber])) {
                continue;
            }

            $previous = $roundMatchesData[$roundNumber - 1];
            $current = &$roundMatchesData[$roundNumber];

            // Separate winners and losers bracket matches
            $winnersMatchIndices = [];
            $losersMatchIndices = [];
            
            foreach ($current as $idx => $matchData) {
                if (isset($matchData['is_double_loser']) && $matchData['is_double_loser'] == 1) {
                    $losersMatchIndices[] = $idx;
                } else {
                    $winnersMatchIndices[] = $idx;
                }
            }

            // Link winners bracket matches (standard 2*idx from previous round winners)
            foreach ($winnersMatchIndices as $position => $idx) {
                $p1Index = $position * 2;
                $p2Index = $position * 2 + 1;

                $refs = [];
                if (isset($previous[$p1Index]) && (!isset($previous[$p1Index]['is_double_loser']) || !$previous[$p1Index]['is_double_loser'])) {
                    $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $p1Index];
                }
                if (isset($previous[$p2Index]) && (!isset($previous[$p2Index]['is_double_loser']) || !$previous[$p2Index]['is_double_loser'])) {
                    $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $p2Index];
                }

                if (!empty($refs)) {
                    $current[$idx]['prev_refs'] = $refs;
                }
            }

            // Link losers bracket matches
            if ($roundNumber === 2) {
                // L1: Losers from W1 paired together
                $winnersLosers = [];
                foreach ($previous as $pIdx => $pMatch) {
                    if (!isset($pMatch['is_double_loser']) || !$pMatch['is_double_loser']) {
                        $winnersLosers[] = $pIdx;
                    }
                }

                foreach ($losersMatchIndices as $position => $idx) {
                    $p1Index = $winnersLosers[$position * 2] ?? null;
                    $p2Index = $winnersLosers[$position * 2 + 1] ?? null;

                    $refs = [];
                    if ($p1Index !== null && isset($previous[$p1Index])) {
                        $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $p1Index];
                    }
                    if ($p2Index !== null && isset($previous[$p2Index])) {
                        $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $p2Index];
                    }

                    if (!empty($refs)) {
                        $current[$idx]['prev_refs'] = $refs;
                    }
                }
            } else if ($roundNumber >= 3) {
                // L2+: Losers from W(n) paired with winners from previous L(n-1)
                $prevWinnersLosers = [];
                $prevLosersWinners = [];

                foreach ($previous as $pIdx => $pMatch) {
                    if (isset($pMatch['is_double_loser']) && $pMatch['is_double_loser'] == 1) {
                        $prevLosersWinners[] = $pIdx;  // Winners from losers bracket
                    } else {
                        $prevWinnersLosers[] = $pIdx;   // Losers from winners bracket
                    }
                }

                // Reverse losers bracket winners for cross-seeding: the SF loser
                // from the top half faces the L1 winner from the bottom half and
                // vice-versa.  This avoids immediate rematches.
                $prevLosersWinnersCrossed = array_reverse($prevLosersWinners);

                foreach ($losersMatchIndices as $position => $idx) {
                    $refs = [];
                    
                    // p1: Loser from previous round's winners bracket
                    if (isset($prevWinnersLosers[$position])) {
                        $winnersLosersIdx = $prevWinnersLosers[$position];
                        $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $winnersLosersIdx];
                    }
                    
                    // p2: Winner from previous losers bracket round (cross-seeded)
                    if (isset($prevLosersWinnersCrossed[$position])) {
                        $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $prevLosersWinnersCrossed[$position]];
                    }

                    if (!empty($refs)) {
                        $current[$idx]['prev_refs'] = $refs;
                    }
                }
            }

            unset($current);
        }

        return $roundMatchesData;
    }
}
