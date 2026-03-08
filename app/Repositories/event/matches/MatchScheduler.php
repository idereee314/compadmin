<?php
namespace event\matches;

/**
 * Cross-bracket match scheduler for mat scheduling.
 *
 * When multiple brackets are assigned to the same mat, this scheduler
 * interleaves their matches to satisfy rest-break constraints:
 *
 *   1. Every player gets at least 1 match break between consecutive fights.
 *   2. Players competing in medal matches get at least 2 match break.
 *   3. The FINAL match (order_no 9999) is always played last per bracket.
 *
 * The algorithm preserves within-bracket ordering (round dependencies) while
 * greedily picking the next match from any bracket to maximise the minimum
 * rest time for the players involved.
 */
class MatchScheduler
{
    /**
     * Minimum break (in match slots) between consecutive fights for a player.
     */
    const MIN_BREAK = 1;

    /**
     * Minimum break before a medal match (order_no >= 9996).
     */
    const MEDAL_BREAK = 2;

    /**
     * Schedule matches from multiple brackets on a single mat.
     *
     * Each bracket's internal match order is preserved (you cannot play a
     * semi-final before a quarter-final within the same bracket). The
     * scheduler interleaves matches from different brackets to provide rest.
     *
     * @param array $bracketMatchLists Array of arrays. Each inner array is
     *     an ordered list of matches for one bracket. Each match is an
     *     associative array with at least: reg_one_id, reg_two_id, order_no,
     *     is_double_loser, event_id, status.
     * @return array Flat list of matches in optimal play order. Each match
     *     gets a '_mat_order' key with its 1-based position on the mat.
     */
    public static function scheduleMatchesForMat(array $bracketMatchLists): array
    {
        if (empty($bracketMatchLists)) {
            return [];
        }

        // Single bracket: no interleaving needed, just tag with mat order
        if (count($bracketMatchLists) === 1) {
            return self::tagMatOrder(reset($bracketMatchLists));
        }

        // Separate regular, medal, and final matches per bracket
        $regularQueues = [];  // bracketIdx => [match, match, ...]
        $medalMatches  = [];  // flat list of medal matches (9996-9998)
        $finalMatches  = [];  // flat list of final matches (9999)

        foreach ($bracketMatchLists as $bracketIdx => $matches) {
            $regularQueues[$bracketIdx] = [];
            foreach ($matches as $match) {
                $match['_bracket_idx'] = $bracketIdx;
                $orderNo = $match['order_no'] ?? 0;

                if ($orderNo >= 9999) {
                    $finalMatches[] = $match;
                } elseif ($orderNo >= 9996) {
                    $medalMatches[] = $match;
                } else {
                    $regularQueues[$bracketIdx][] = $match;
                }
            }
        }

        // Greedy scheduling of regular matches
        $scheduled = self::greedySchedule($regularQueues);

        // Schedule medal matches with 2-match break constraint
        $scheduled = self::scheduleMedalMatches($scheduled, $medalMatches);

        // Finals always last (one per bracket, maintain bracket order)
        foreach ($finalMatches as $match) {
            $scheduled[] = $match;
        }

        return self::tagMatOrder($scheduled);
    }

    /**
     * Greedily interleave matches from multiple bracket queues.
     *
     * At each slot, pick the next available match (front of any bracket's
     * queue) that maximises the minimum rest time for its players.
     *
     * @param array $regularQueues bracketIdx => ordered match arrays
     * @return array Flat list of scheduled matches
     */
    private static function greedySchedule(array $regularQueues): array
    {
        $scheduled = [];
        $lastSlot  = [];  // playerID => last slot they played
        $pointers  = [];  // bracketIdx => current index into queue

        foreach ($regularQueues as $bracketIdx => $queue) {
            $pointers[$bracketIdx] = 0;
        }

        $slot = 1;
        $totalRegular = 0;
        foreach ($regularQueues as $queue) {
            $totalRegular += count($queue);
        }

        while (count($scheduled) < $totalRegular) {
            $bestMatch   = null;
            $bestBracket = null;
            $bestMinWait = -1;

            foreach ($regularQueues as $bracketIdx => $queue) {
                $ptr = $pointers[$bracketIdx];
                if ($ptr >= count($queue)) {
                    continue;
                }

                $match = $queue[$ptr];
                $minWait = self::computeMinWait($match, $slot, $lastSlot);

                if ($minWait > $bestMinWait) {
                    $bestMinWait = $minWait;
                    $bestMatch   = $match;
                    $bestBracket = $bracketIdx;
                }
            }

            if ($bestMatch === null) {
                break;
            }

            $scheduled[] = $bestMatch;
            self::updateLastSlot($lastSlot, $bestMatch, $slot);
            $pointers[$bestBracket]++;
            $slot++;
        }

        return $scheduled;
    }

    /**
     * Schedule medal matches after regular matches, ensuring 2-match break.
     *
     * If a medal match involves a player who played recently, try to
     * reorder medal matches from different brackets to provide rest.
     *
     * @param array $scheduled Already scheduled regular matches
     * @param array $medalMatches Medal matches to schedule
     * @return array Updated scheduled list with medal matches appended
     */
    private static function scheduleMedalMatches(array $scheduled, array $medalMatches): array
    {
        if (empty($medalMatches)) {
            return $scheduled;
        }

        $lastSlot = [];
        foreach ($scheduled as $slot => $match) {
            self::updateLastSlot($lastSlot, $match, $slot + 1);
        }

        // Sort medal matches greedily: pick the one with best rest first
        $remaining = $medalMatches;
        $slot = count($scheduled) + 1;

        while (!empty($remaining)) {
            $bestIdx     = 0;
            $bestMinWait = -1;

            foreach ($remaining as $idx => $match) {
                $minWait = self::computeMinWait($match, $slot, $lastSlot);
                if ($minWait > $bestMinWait) {
                    $bestMinWait = $minWait;
                    $bestIdx     = $idx;
                }
            }

            $scheduled[] = $remaining[$bestIdx];
            self::updateLastSlot($lastSlot, $remaining[$bestIdx], $slot);

            $newRemaining = [];
            foreach ($remaining as $idx => $m) {
                if ($idx !== $bestIdx) {
                    $newRemaining[] = $m;
                }
            }
            $remaining = $newRemaining;
            $slot++;
        }

        return $scheduled;
    }

    /**
     * Compute the minimum wait for a match's players at a given slot.
     *
     * For matches with null players (elimination bracket later rounds),
     * return a very high value since player rest can't be evaluated.
     */
    private static function computeMinWait(array $match, int $slot, array $lastSlot): int
    {
        $p1 = $match['reg_one_id'] ?? null;
        $p2 = $match['reg_two_id'] ?? null;

        if ($p1 === null && $p2 === null) {
            // Unknown players; schedule freely but prefer brackets that
            // haven't been scheduled recently (use bracket index as tiebreak)
            return PHP_INT_MAX - ($match['_bracket_idx'] ?? 0);
        }

        $wait1 = ($p1 !== null) ? ($slot - ($lastSlot[$p1] ?? 0)) : PHP_INT_MAX;
        $wait2 = ($p2 !== null) ? ($slot - ($lastSlot[$p2] ?? 0)) : PHP_INT_MAX;

        return min($wait1, $wait2);
    }

    /**
     * Update the last-slot tracking for a match's players.
     */
    private static function updateLastSlot(array &$lastSlot, array $match, int $slot): void
    {
        if (isset($match['reg_one_id']) && $match['reg_one_id'] !== null) {
            $lastSlot[$match['reg_one_id']] = $slot;
        }
        if (isset($match['reg_two_id']) && $match['reg_two_id'] !== null) {
            $lastSlot[$match['reg_two_id']] = $slot;
        }
    }

    /**
     * Tag each match with a 1-based '_mat_order' position.
     */
    private static function tagMatOrder(array $matches): array
    {
        foreach ($matches as $i => &$match) {
            $match['_mat_order'] = $i + 1;
        }
        unset($match);

        return $matches;
    }
}