<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\MatchScheduler;

/**
 * Tests for cross-bracket mat scheduling.
 *
 * When multiple brackets are assigned to the same mat, the MatchScheduler
 * interleaves their matches so every player gets rest breaks.
 */
class MatchSchedulerTest extends TestCase
{
    // =========================================================================
    // Basic functionality
    // =========================================================================

    public function test_empty_input_returns_empty()
    {
        $this->assertEmpty(MatchScheduler::scheduleMatchesForMat([]));
    }

    public function test_single_bracket_preserves_order()
    {
        $bracket = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 3, 'reg_two_id' => 4, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket]);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]['reg_one_id']);
        $this->assertEquals(3, $result[1]['reg_one_id']);
    }

    public function test_mat_order_tags_assigned()
    {
        $bracket = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 3, 'reg_two_id' => 4, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket]);

        $this->assertEquals(1, $result[0]['_mat_order']);
        $this->assertEquals(2, $result[1]['_mat_order']);
    }

    // =========================================================================
    // Two brackets interleaving
    // =========================================================================

    public function test_two_3man_rr_brackets_interleaved()
    {
        // Bracket 1: 3-player round-robin (A1vA2, A1vA3, A2vA3)
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 3, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 2, 'reg_two_id' => 3, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        // Bracket 2: 3-player round-robin (B1vB2, B1vB3, B2vB3)
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 30, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 20, 'reg_two_id' => 30, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2]);

        $this->assertCount(6, $result);

        // Check that player 1 (bracket 1) has at least 1 match break between fights
        $p1Slots = [];
        foreach ($result as $i => $m) {
            if ($m['reg_one_id'] === 1 || $m['reg_two_id'] === 1) {
                $p1Slots[] = $i + 1;
            }
        }
        $this->assertCount(2, $p1Slots);
        $this->assertGreaterThanOrEqual(2, $p1Slots[1] - $p1Slots[0],
            'Player 1 needs 1+ match break between fights');

        // Same for player 10 (bracket 2)
        $p10Slots = [];
        foreach ($result as $i => $m) {
            if ($m['reg_one_id'] === 10 || $m['reg_two_id'] === 10) {
                $p10Slots[] = $i + 1;
            }
        }
        $this->assertCount(2, $p10Slots);
        $this->assertGreaterThanOrEqual(2, $p10Slots[1] - $p10Slots[0],
            'Player 10 needs 1+ match break between fights');
    }

    public function test_within_bracket_order_preserved()
    {
        // Bracket with R1 and R2 matches in dependency order
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 3, 'reg_two_id' => 4, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 201, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2]);

        // Find positions of bracket 1's matches
        $b1Positions = [];
        foreach ($result as $i => $m) {
            if (($m['_bracket_idx'] ?? null) === 0) {
                $b1Positions[] = $m['order_no'];
            }
        }

        // R1 matches must come before R2 match within bracket 1
        $r1Pos = array_search(1, array_column($result, 'order_no'));
        $r2Pos = array_search(2, array_column($result, 'order_no'));
        $sfPos = array_search(201, array_column($result, 'order_no'));

        $this->assertLessThan($sfPos, $r1Pos, 'R1M1 must play before SF');
        $this->assertLessThan($sfPos, $r2Pos, 'R1M2 must play before SF');
    }

    // =========================================================================
    // Medal match handling
    // =========================================================================

    public function test_finals_always_last()
    {
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9999, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 30, 'reg_two_id' => 40, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9999, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2]);

        // Both finals should be at the end
        $lastTwo = array_slice($result, -2);
        foreach ($lastTwo as $m) {
            $this->assertEquals(9999, $m['order_no'], 'Finals must be at the end');
        }
    }

    public function test_medal_matches_before_finals()
    {
        $bracket = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 3, 'reg_two_id' => 4, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9998, 'is_double_loser' => 1, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9999, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket]);

        // Bronze before Gold
        $bronzePos = null;
        $goldPos = null;
        foreach ($result as $i => $m) {
            if ($m['order_no'] === 9998) $bronzePos = $i;
            if ($m['order_no'] === 9999) $goldPos = $i;
        }

        $this->assertNotNull($bronzePos);
        $this->assertNotNull($goldPos);
        $this->assertLessThan($goldPos, $bronzePos, 'Bronze must play before Gold');
    }

    // =========================================================================
    // Break quality across brackets
    // =========================================================================

    public function test_3man_bracket_first_gets_breaks_with_second_bracket()
    {
        // This is the specific case the user mentioned: 3-man brackets placed first
        // on a mat get no rest. Adding a second bracket should provide interleaving.
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 3, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 2, 'reg_two_id' => 3, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        // Second bracket with 4 players
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 30, 'reg_two_id' => 40, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9999, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2]);

        // Player 1 from bracket 1 plays 2 matches. With interleaving from bracket 2,
        // they should have at least 1 match break between fights.
        $p1Slots = [];
        foreach ($result as $i => $m) {
            if (($m['reg_one_id'] ?? null) === 1 || ($m['reg_two_id'] ?? null) === 1) {
                $p1Slots[] = $i + 1;
            }
        }

        $this->assertCount(2, $p1Slots, 'Player 1 should have exactly 2 matches');
        $this->assertGreaterThanOrEqual(2, $p1Slots[1] - $p1Slots[0],
            'Player 1 should have 1+ match break between fights when second bracket provides interleaving');
    }

    public function test_all_matches_from_all_brackets_included()
    {
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => null, 'reg_two_id' => null, 'order_no' => 9999, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 30, 'reg_two_id' => 40, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket3 = [
            ['event_id' => 1, 'reg_one_id' => 100, 'reg_two_id' => 200, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2, $bracket3]);

        $this->assertCount(5, $result, 'All 5 matches from 3 brackets should be scheduled');
    }

    public function test_three_brackets_interleaved()
    {
        $bracket1 = [
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 2, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 1, 'reg_two_id' => 3, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 2, 'reg_two_id' => 3, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket2 = [
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 20, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 10, 'reg_two_id' => 30, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 20, 'reg_two_id' => 30, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];
        $bracket3 = [
            ['event_id' => 1, 'reg_one_id' => 100, 'reg_two_id' => 200, 'order_no' => 1, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 100, 'reg_two_id' => 300, 'order_no' => 2, 'is_double_loser' => 0, 'status' => 'P'],
            ['event_id' => 1, 'reg_one_id' => 200, 'reg_two_id' => 300, 'order_no' => 3, 'is_double_loser' => 0, 'status' => 'P'],
        ];

        $result = MatchScheduler::scheduleMatchesForMat([$bracket1, $bracket2, $bracket3]);

        $this->assertCount(9, $result);

        // With 3 brackets interleaved, every player in every bracket should
        // get at least 1 match break between fights
        $allPlayers = [1, 2, 3, 10, 20, 30, 100, 200, 300];
        foreach ($allPlayers as $p) {
            $slots = [];
            foreach ($result as $i => $m) {
                if (($m['reg_one_id'] ?? null) === $p || ($m['reg_two_id'] ?? null) === $p) {
                    $slots[] = $i + 1;
                }
            }

            for ($i = 0; $i < count($slots) - 1; $i++) {
                $gap = $slots[$i + 1] - $slots[$i];
                $this->assertGreaterThanOrEqual(2, $gap,
                    "Player $p: gap between slot {$slots[$i]} and {$slots[$i+1]} is only $gap (need ≥2)");
            }
        }
    }
}