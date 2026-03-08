<?php
namespace event\matches;

use event\EventBrackets;
use event\EventMatches;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * MJJF / IJF Elimination Strategy
 *
 * Partial repechage: only quarterfinal (QF) losers go to the lower bracket.
 * All players who lose before QF are eliminated immediately.
 * Applies to 16-man and above brackets.
 *
 * Structure (16 players example):
 *   R1: 8 matches (1/8)        → losers eliminated
 *   QF: 4 matches (1/4)        → winners to SF, losers to Repechage
 *   SF: 2 matches               → winners to Final, losers to Bronze
 *   Repechage: 2 matches        → QF losers paired (runs parallel to SF)
 *   Final: 1 match (gold)       → from SF winners
 *   Bronze: 2 matches           → SF losers vs Repechage winners
 *
 * For 8-man brackets the QF IS round 1, so all first-round losers go to
 * repechage — effectively the same as double elimination for 8 players.
 */
class MJJFEliminationStrategy implements TournamentEliminationStrategy {

    public function __construct($eliminationType = 'mjjf')
    {
        // Type accepted for factory compatibility
    }

    public function generateBracket($eventId, $participants, $config)
    {
        $validation = $this->validateConfig($config);
        if (!$validation['valid']) {
            Log::error('Invalid configuration for MJJF Elimination', $validation['errors']);
            return [];
        }

        $participantCount = count($participants);
        if ($participantCount < 2) {
            Log::error('Minimum 2 participants required for MJJF Elimination');
            return [];
        }

        $bracketStructure = [];
        $rounds = $this->calculateTotalRounds($participantCount);
        $positions = $this->calculateBracketPositions($participantCount);

        foreach ($participants as $index => $participant) {
            $bracketStructure[] = [
                'event_id' => $eventId,
                'participant_id' => $participant['id'] ?? $participant,
                'position' => $positions[$index] ?? $index + 1,
                'round' => 1,
                'status' => 'P',
                'total_rounds' => $rounds,
            ];
        }

        return $bracketStructure;
    }

    public function generateRoundMatches($eventId, $roundNumber, $participantRegistrations = [])
    {
        $matches = [];

        if ($roundNumber === 1) {
            return $this->generateFirstRoundMatches($eventId, $participantRegistrations);
        }

        $matchesInFirstRound = count($participantRegistrations) >= 2 ? floor(count($participantRegistrations) / 2) : 1;
        $totalRounds = $this->calculateTotalRounds(count($participantRegistrations));
        $winnersBracketMatches = floor($matchesInFirstRound / pow(2, $roundNumber - 1));

        // Identify key rounds:
        // QF round = totalRounds - 2 (quarterfinals, always 4 matches)
        // SF round = totalRounds - 1 (semifinals, always 2 matches)
        // Final round = totalRounds   (final + bronze matches)
        $sfRound = $totalRounds - 1;
        $finalRound = $totalRounds;

        if ($roundNumber === $finalRound) {
            // Final round: 2 Bronze matches first, then Gold match last.
            // Bronze matches: each SF loser vs a Repechage winner.
            // Ordered so cross-seeded bronze is played first, giving both
            // bronze players a chance at rest before the final.
            for ($i = 0; $i < 2; $i++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => null,
                    'reg_two_id' => null,
                    'order_no' => 9997 - $i,  // 9997, 9996
                    'status' => 'P',
                    'is_double_loser' => 1,
                ];
            }
            // Gold: SF winners — always played last
            $matches[] = [
                'event_id' => $eventId,
                'reg_one_id' => null,
                'reg_two_id' => null,
                'order_no' => 9999,
                'status' => 'P',
                'is_double_loser' => 0,
            ];
        } else if ($roundNumber === $sfRound) {
            // SF round: Repechage matches FIRST, then SF matches.
            // Playing repechage before SF gives repechage winners more rest
            // before their bronze match (at least 2 match break).
            // Repechage: 4 QF losers → 2 matches (same-half pairing)
            for ($i = 0; $i < 2; $i++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => null,
                    'reg_two_id' => null,
                    'order_no' => 2000 + $roundNumber * 100 + $i + 1,
                    'status' => 'P',
                    'is_double_loser' => 1,
                ];
            }
            // SF matches after repechage
            for ($i = 0; $i < $winnersBracketMatches; $i++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => null,
                    'reg_two_id' => null,
                    'order_no' => $roundNumber * 100 + $i + 1,
                    'status' => 'P',
                    'is_double_loser' => 0,
                ];
            }
        } else {
            // Regular winners bracket matches
            for ($i = 0; $i < $winnersBracketMatches; $i++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => null,
                    'reg_two_id' => null,
                    'order_no' => $roundNumber * 100 + $i + 1,
                    'status' => 'P',
                    'is_double_loser' => 0,
                ];
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

        // Apply tournament seeding for 8+ player power-of-2 brackets
        $participantRegistrations = MatchScheduler::seedParticipants($participantRegistrations);

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
     * Compute previous-match references for MJJF/IJF bracket.
     *
     * Winners bracket: standard 2*idx linking from previous W matches.
     * Repechage (SF round): fed by QF losers (cross-seeded).
     * Bronze (Final round): p1 = SF loser, p2 = Repechage winner.
     * Final (Final round): p1 = SF winner 1, p2 = SF winner 2.
     */
    public function computePreviousReferences($roundMatchesData)
    {
        $roundNumbers = array_keys($roundMatchesData);
        sort($roundNumbers);
        $totalRounds = end($roundNumbers);
        $sfRound = $totalRounds - 1;
        $finalRound = $totalRounds;

        foreach ($roundNumbers as $roundNumber) {
            if ($roundNumber <= 1) {
                continue;
            }

            if (!isset($roundMatchesData[$roundNumber - 1]) || !isset($roundMatchesData[$roundNumber])) {
                continue;
            }

            $previous = $roundMatchesData[$roundNumber - 1];
            $current = &$roundMatchesData[$roundNumber];

            // Separate winners and losers bracket matches in current and previous rounds
            $winnersMatchIndices = [];
            $losersMatchIndices = [];
            foreach ($current as $idx => $matchData) {
                if (isset($matchData['is_double_loser']) && $matchData['is_double_loser'] == 1) {
                    $losersMatchIndices[] = $idx;
                } else {
                    $winnersMatchIndices[] = $idx;
                }
            }

            $prevWinnersIndices = [];
            $prevLosersIndices = [];
            foreach ($previous as $pIdx => $pMatch) {
                if (isset($pMatch['is_double_loser']) && $pMatch['is_double_loser'] == 1) {
                    $prevLosersIndices[] = $pIdx;
                } else {
                    $prevWinnersIndices[] = $pIdx;
                }
            }

            // Link winners bracket matches (standard 2*position)
            foreach ($winnersMatchIndices as $position => $idx) {
                $p1Index = $prevWinnersIndices[$position * 2] ?? null;
                $p2Index = $prevWinnersIndices[$position * 2 + 1] ?? null;

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

            if ($roundNumber === $sfRound && !empty($losersMatchIndices)) {
                // Repechage round: 2 matches fed by 4 QF losers (same-half pairing)
                // QF match order: [QF1, QF2, QF3, QF4]
                // Rep1: QF1 loser vs QF2 loser (top half)
                // Rep2: QF3 loser vs QF4 loser (bottom half)
                foreach ($losersMatchIndices as $position => $idx) {
                    $p1Index = $prevWinnersIndices[$position * 2] ?? null;
                    $p2Index = $prevWinnersIndices[$position * 2 + 1] ?? null;

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
            } elseif ($roundNumber === $finalRound && !empty($losersMatchIndices)) {
                // Bronze matches: cross-seeded (same as DoubleEliminationStrategy)
                // p1 = SF loser (REVERSED), p2 = Repechage winner (sequential)
                // Bronze1: SF2 loser vs Rep1 winner (bottom SF vs top repechage)
                // Bronze2: SF1 loser vs Rep2 winner (top SF vs bottom repechage)
                $prevWinnersCrossed = array_reverse($prevWinnersIndices);

                foreach ($losersMatchIndices as $position => $idx) {
                    $refs = [];
                   // SF match (winners bracket, cross-seeded) — loser goes to bronze via $shouldSendLoser
                    if (isset($prevWinnersCrossed[$position])) {
                        $refs['p1'] = ['round' => $roundNumber - 1, 'index' => $prevWinnersCrossed[$position]];
                    }
                    // Repechage match (losers bracket) — winner goes to bronze
                    if (isset($prevLosersIndices[$position])) {
                        $refs['p2'] = ['round' => $roundNumber - 1, 'index' => $prevLosersIndices[$position]];
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
        return 'mjjf_elimination';
    }

    public function calculateTotalRounds($participantCount)
    {
        if ($participantCount <= 1) {
            return 0;
        }
        // Same as single elimination: ceil(log2(n))
        // Repechage + Bronze run parallel within existing rounds
        return ceil(log($participantCount, 2));
    }

    private function calculateBracketPositions($participantCount)
    {
        $positions = [];
        for ($i = 0; $i < $participantCount; $i++) {
            $positions[] = $i + 1;
        }
        return $positions;
    }
}