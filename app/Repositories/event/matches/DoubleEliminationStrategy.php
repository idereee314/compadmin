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
     * Whether this is the "1 bronze" variant (single bronze match)
     * vs the default "2 bronze" variant (two separate bronze matches)
     */
    private $singleBronze = false;

    /**
     * Whether pool format is active (6 players → 2 pools of 3)
     */
    private $poolFormat = false;

    public function __construct($eliminationType = 'double')
    {
        $this->singleBronze = ($eliminationType === 'double_single_bronze');
    }

    /**
     * Check if the participant count requires pool-based format
     * 6 players: 2 pools of 3 with round-robin, then cross-pool semi-finals
     */
    private function isPoolFormat($participantCount)
    {
        return $participantCount === 6;
    }

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
        $participantCount = count($participantRegistrations);

        // For 6 players, use pool-based format
        if ($this->isPoolFormat($participantCount)) {
            $this->poolFormat = true;
            return $this->generatePoolFormatRoundMatches($eventId, $roundNumber, $participantRegistrations);
        }

        // Round 1: Winners bracket only, same as single elimination
        if ($roundNumber === 1) {
            return $this->generateFirstRoundMatches($eventId, $participantRegistrations);
        }

        // Rounds 2+: Winners bracket + Losers bracket
        // Winners bracket follows single elimination pattern
        $matchesInFirstRound = count($participantRegistrations) >= 2 ? floor(count($participantRegistrations) / 2) : 1;
        $winnersBracketMatches = floor($matchesInFirstRound / pow(2, $roundNumber - 1));

        // Winners bracket matches (identical to single elimination)
        // When only 1 W match remains, it's the Gold (final) match
        for ($i = 0; $i < $winnersBracketMatches; $i++) {
            $isGoldMatch = ($winnersBracketMatches === 1);
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => $isGoldMatch ? 9999 : ($roundNumber * 100 + $i + 1),
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
                // "2 bronze": Final losers round with 2 matches becomes bronze (order_no 9997, 9996)
                // "1 bronze": Those 2 matches are L semi-finals; a single bronze match follows in the next round
                $isFinalLosersRound = !$this->singleBronze && ($lnMatches === 2);

                // "1 bronze": The extra round has 0 W matches and 1 L match — this is the single bronze match
                $isBronzeMatch = $this->singleBronze && $winnersBracketMatches === 0 && $lnMatches === 1;

                for ($i = 0; $i < $lnMatches; $i++) {
                    if ($isFinalLosersRound) {
                        $orderNo = 9997 - $i;  // 2 bronze matches: 9997, 9996
                    } elseif ($isBronzeMatch) {
                        $orderNo = 9998;  // Single bronze match
                    } else {
                        $orderNo = 2000 + ($roundNumber * 100) + $i + 1;
                    }

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

    /**
     * Generate matches for pool-based format (6 players)
     *
     * Round 1: 2 pools of 3, round-robin within each pool (6 matches total)
     * Round 2: Cross-pool semi-finals (2 matches)
     *   Match 7: 1st Pool1 vs 2nd Pool2
     *   Match 8: 1st Pool2 vs 2nd Pool1
     * Round 3: Final + Bronze (2 matches)
     *   Match 9: Winner M7 vs Winner M8 (Gold)
     *   Match 10: Loser M7 vs Loser M8 (Bronze)
     */
    private function generatePoolFormatRoundMatches($eventId, $roundNumber, $participantRegistrations)
    {
        $matches = [];

        if ($roundNumber === 1) {
            // Split into 2 pools of 3
            $pool1 = array_slice($participantRegistrations, 0, 3);
            $pool2 = array_slice($participantRegistrations, 3, 3);

            $matchOrder = 1;

            // Pool 1 round-robin: 3 matches
            for ($i = 0; $i < count($pool1); $i++) {
                for ($j = $i + 1; $j < count($pool1); $j++) {
                    $matches[] = [
                        'event_id' => $eventId,
                        'reg_one_id' => $pool1[$i],
                        'reg_two_id' => $pool1[$j],
                        'previes_mate_id1' => null,
                        'previes_mate_id2' => null,
                        'order_no' => $matchOrder++,
                        'status' => 'P',
                        'is_double_loser' => 0,
                    ];
                }
            }

            // Pool 2 round-robin: 3 matches
            for ($i = 0; $i < count($pool2); $i++) {
                for ($j = $i + 1; $j < count($pool2); $j++) {
                    $matches[] = [
                        'event_id' => $eventId,
                        'reg_one_id' => $pool2[$i],
                        'reg_two_id' => $pool2[$j],
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

        if ($roundNumber === 2) {
            // Semi-finals: cross-pool matches
            // Match 7: 1st Pool1 vs 2nd Pool2
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => 201,
                'status' => 'P',
                'is_double_loser' => 0,
            ];
            // Match 8: 1st Pool2 vs 2nd Pool1
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => 202,
                'status' => 'P',
                'is_double_loser' => 0,
            ];

            return $matches;
        }

        if ($roundNumber === 3) {
            // Match 9: Final (Gold) - Winner M7 vs Winner M8
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => 9999,
                'status' => 'P',
                'is_double_loser' => 0,
            ];

            // Match 10: Bronze - Loser M7 vs Loser M8
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => 9998,
                'status' => 'P',
                'is_double_loser' => 1,
            ];

            return $matches;
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

        // Pool format (6 players): 3 rounds (Pool RR, Semi-finals, Final+Bronze)
        if ($this->isPoolFormat($participantCount)) {
            return 3;
        }

        // Double elimination winners bracket = same as single elimination rounds
        // For 8: ceil(log2(8)) = 3 rounds
        // Losers bracket runs parallel (L1 in round 2, L2 in round 3, etc.)
        $rounds = ceil(log($participantCount, 2));

        // "1 bronze" variant: add an extra round for the single bronze match
        // (winners of final losers bracket matches fight for bronze)
        if ($this->singleBronze) {
            $rounds += 1;
        }

        return $rounds;
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
        // Pool format: only final round (round 3) links to semi-finals (round 2)
        // Pool matches (round 1) and semi-finals (round 2) have no prev_refs
        if ($this->poolFormat) {
            return $this->computePoolFormatPreviousReferences($roundMatchesData);
        }

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

                // "1 bronze" extra round: single bronze match fed by two previous L bracket winners
                if ($this->singleBronze && empty($winnersMatchIndices) && count($losersMatchIndices) === 1) {
                    $bronzeIdx = $losersMatchIndices[0];
                    $refs = [];
                    if (isset($prevLosersWinners[0])) {
                        $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $prevLosersWinners[0]];
                    }
                    if (isset($prevLosersWinners[1])) {
                        $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $prevLosersWinners[1]];
                    }
                    if (!empty($refs)) {
                        $current[$bronzeIdx]['prev_refs'] = $refs;
                    }
                } else {
                    // Cross-seed by reversing the winners bracket losers:
                    // Bottom-half SF loser faces top-half L1 winner, and vice-versa.
                    // For 8 players this produces:
                    //   Match 9:  Loser SF-B  vs  Winner M7  (bottom SF vs top L1)
                    //   Match 10: Loser SF-A  vs  Winner M8  (top SF vs bottom L1)
                    $prevWinnersLosersCrossed = array_reverse($prevWinnersLosers);

                    foreach ($losersMatchIndices as $position => $idx) {
                        $refs = [];

                        // p1: Loser from previous round's winners bracket (cross-seeded)
                        if (isset($prevWinnersLosersCrossed[$position])) {
                            $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $prevWinnersLosersCrossed[$position]];
                        }

                        // p2: Winner from previous losers bracket round
                        if (isset($prevLosersWinners[$position])) {
                            $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $prevLosersWinners[$position]];
                        }

                        if (!empty($refs)) {
                            $current[$idx]['prev_refs'] = $refs;
                        }
                    }
                }
            }

            unset($current);
        }

        return $roundMatchesData;
    }

    /**
     * Compute previous-match references for pool format (6 players)
     *
     * Pool matches (round 1) have no prev_refs - all players are known upfront.
     * Semi-finals (round 2) have no prev_refs - players come from pool standings.
     * Final and bronze (round 3) link to the two semi-final matches:
     *   Gold (9999): winners of both semi-finals
     *   Bronze (9998): losers of both semi-finals
     */
    private function computePoolFormatPreviousReferences($roundMatchesData)
    {
        if (!isset($roundMatchesData[3]) || !isset($roundMatchesData[2])) {
            return $roundMatchesData;
        }

        $current = &$roundMatchesData[3];

        // Both final (Gold) and bronze link to the 2 semi-final matches in round 2
        foreach ($current as $idx => &$match) {
            $refs = [];
            if (isset($roundMatchesData[2][0])) {
                $refs['p1'] = ['round' => 2, 'index' => 0];
            }
            if (isset($roundMatchesData[2][1])) {
                $refs['p2'] = ['round' => 2, 'index' => 1];
            }

            if (!empty($refs)) {
                $match['prev_refs'] = $refs;
            }
        }
        unset($match);
        unset($current);

        return $roundMatchesData;
    }
}