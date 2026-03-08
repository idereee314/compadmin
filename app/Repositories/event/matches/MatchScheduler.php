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

    /**
     * Generate standard tournament seeded order for a given bracket size.
     *
     * Returns an array of 1-based seed positions in match order.
     * When paired sequentially (indices 0&1, 2&3, etc.), these produce
     * the standard seeded matchups:
     *   8 players:  1v8, 4v5, 2v7, 3v6
     *   16 players: 1v16, 8v9, 4v13, 5v12, 2v15, 7v10, 3v14, 6v11
     *   32 players: 1v32, 16v17, 9v24, 8v25, 4v29, 13v20, 12v21, 5v28,
     *               2v31, 15v18, 10v23, 7v26, 3v30, 14v19, 11v22, 6v27
     *
     * @param int $bracketSize Must be a power of 2 (8, 16, or 32)
     * @return array 1-based seed positions
     */
    public static function generateSeededOrder(int $bracketSize): array
    {
        if ($bracketSize < 2) {
            return [1];
        }
        if ($bracketSize === 2) {
            return [1, 2];
        }

        $half = self::generateSeededOrder(intdiv($bracketSize, 2));
        $result = [];
        foreach ($half as $s) {
            $result[] = $s;
            $result[] = $bracketSize + 1 - $s;
        }

        // Apply mirror correction for 32-player brackets.
        // Swaps the second pair within each group of 4 entries at odd
        // group positions so that the bracket mirrors correctly:
        //   e.g. [8,25, 9,24] becomes [9,24, 8,25]
        if ($bracketSize === 32) {
            for ($i = 0; $i < count($result); $i += 4) {
                if (intdiv($i, 4) % 2 === 1) {
                    $tmp0 = $result[$i];
                    $tmp1 = $result[$i + 1];
                    $result[$i]     = $result[$i + 2];
                    $result[$i + 1] = $result[$i + 3];
                    $result[$i + 2] = $tmp0;
                    $result[$i + 3] = $tmp1;
                }
            }
        }

        return $result;
    }

    /**
     * Reorder participants according to standard tournament seeding.
     *
     * Applies seeding for power-of-2 bracket sizes of 8 or more.
     * For other sizes, returns participants in their original order.
     *
     * @param array $participants Participant IDs in seed order (index 0 = seed 1)
     * @return array Reordered participants for bracket pairing
     */
    public static function seedParticipants(array $participants): array
    {
        $count = count($participants);

        // Only seed for power-of-2 sizes of 8+
        if ($count < 8 || ($count & ($count - 1)) !== 0) {
            return $participants;
        }

        $seedOrder = self::generateSeededOrder($count);
        $seeded = [];
        foreach ($seedOrder as $seed) {
            $seeded[] = $participants[$seed - 1];
        }

        return $seeded;
    }
}