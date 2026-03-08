<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\DoubleEliminationStrategy;
use event\matches\TournamentEliminationStrategyFactory;

/**
 * Tests for small double-elimination bracket sizes:
 *   2 players  → best-of-3
 *   3–5 players → full round-robin
 *
 * These rules apply to BOTH 'double' and 'double_single_bronze' variants.
 * Only 1 bronze medalist in all double-elimination variants; for round-robin
 * medals are determined by final standings, not by a separate play-off match.
 */
class DoubleEliminationSmallBracketTest extends TestCase
{
    protected $double;
    protected $singleBronze;

    protected function setUp(): void
    {
        parent::setUp();
        $this->double       = new DoubleEliminationStrategy('double');
        $this->singleBronze = new DoubleEliminationStrategy('double_single_bronze');
    }

    // =========================================================================
    // calculateTotalRounds
    // =========================================================================

    public function test_total_rounds_2_players_is_1()
    {
        $this->assertEquals(1, $this->double->calculateTotalRounds(2));
        $this->assertEquals(1, $this->singleBronze->calculateTotalRounds(2));
    }

    public function test_total_rounds_3_players_is_1()
    {
        $this->assertEquals(1, $this->double->calculateTotalRounds(3));
        $this->assertEquals(1, $this->singleBronze->calculateTotalRounds(3));
    }

    public function test_total_rounds_4_players_is_1()
    {
        $this->assertEquals(1, $this->double->calculateTotalRounds(4));
        $this->assertEquals(1, $this->singleBronze->calculateTotalRounds(4));
    }

    public function test_total_rounds_5_players_is_1()
    {
        $this->assertEquals(1, $this->double->calculateTotalRounds(5));
        $this->assertEquals(1, $this->singleBronze->calculateTotalRounds(5));
    }

    // =========================================================================
    // Best-of-3 (2 players)
    // =========================================================================

    public function test_best_of_3_generates_3_match_slots()
    {
        $players = [10, 20];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $this->assertCount(3, $matches);
    }

    public function test_best_of_3_same_for_both_variants()
    {
        $players = [10, 20];
        $a = $this->double->generateRoundMatches(1, 1, $players);
        $b = $this->singleBronze->generateRoundMatches(1, 1, $players);
        $this->assertEquals($a, $b);
    }

    public function test_best_of_3_match1_order_no_is_1()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        $this->assertEquals(1, $matches[0]['order_no']);
    }

    public function test_best_of_3_match2_order_no_is_3_leaving_1_slot_break()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        // Slot 2 is deliberately left empty for a 1-match break
        $this->assertEquals(3, $matches[1]['order_no']);
    }

    public function test_best_of_3_match3_gold_order_no_is_9999()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        // Gold/Final — scheduled last to provide 2+ match break from match 2
        $this->assertEquals(9999, $matches[2]['order_no']);
    }

    public function test_best_of_3_match1_p1_is_red_p2_is_blue()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        // Match 1: P1 (10) = RED (reg_one), P2 (20) = BLUE (reg_two)
        $this->assertEquals(10, $matches[0]['reg_one_id']);
        $this->assertEquals(20, $matches[0]['reg_two_id']);
    }

    public function test_best_of_3_match2_corners_swapped()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        // Match 2: corners swapped — P2 (20) = RED (reg_one), P1 (10) = BLUE (reg_two)
        $this->assertEquals(20, $matches[1]['reg_one_id']);
        $this->assertEquals(10, $matches[1]['reg_two_id']);
    }

    public function test_best_of_3_match3_has_players_assigned()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        // Match 3 (decider, conditional): players assigned; operator uses SWITCH SIDES
        // for random corner assignment at the mat.
        $this->assertNotNull($matches[2]['reg_one_id']);
        $this->assertNotNull($matches[2]['reg_two_id']);
    }

    public function test_best_of_3_round2_returns_empty()
    {
        $this->assertEmpty($this->double->generateRoundMatches(1, 2, [10, 20]));
        $this->assertEmpty($this->singleBronze->generateRoundMatches(1, 2, [10, 20]));
    }

    public function test_best_of_3_no_prev_match_references()
    {
        $matches = $this->double->generateRoundMatches(1, 1, [10, 20]);
        foreach ($matches as $m) {
            $this->assertNull($m['previes_mate_id1']);
            $this->assertNull($m['previes_mate_id2']);
        }
    }

    // =========================================================================
    // Round-robin — 3 players (3 matches)
    // =========================================================================

    public function test_rr_3_players_generates_3_matches()
    {
        $players = [1, 2, 3];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $this->assertCount(3, $matches);
    }

    public function test_rr_3_players_all_pairs_covered()
    {
        $players = [1, 2, 3];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $pairs = array_map(fn($m) => [$m['reg_one_id'], $m['reg_two_id']], $matches);

        $this->assertContains([1, 2], $pairs);
        $this->assertContains([1, 3], $pairs);
        $this->assertContains([2, 3], $pairs);
    }

    public function test_rr_3_players_order_nos_are_1_2_3()
    {
        $players = [1, 2, 3];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $orderNos = array_column($matches, 'order_no');
        sort($orderNos);
        $this->assertEquals([1, 2, 3], $orderNos);
    }

    public function test_rr_3_players_round2_returns_empty()
    {
        $this->assertEmpty($this->double->generateRoundMatches(1, 2, [1, 2, 3]));
    }

    // =========================================================================
    // Round-robin — 4 players (6 matches)
    // =========================================================================

    public function test_rr_4_players_generates_6_matches()
    {
        $players = [1, 2, 3, 4];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $this->assertCount(6, $matches);
    }

    public function test_rr_4_players_all_pairs_covered()
    {
        $players = [1, 2, 3, 4];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $pairs = array_map(fn($m) => [$m['reg_one_id'], $m['reg_two_id']], $matches);

        $expectedPairs = [[1,2],[1,3],[1,4],[2,3],[2,4],[3,4]];
        foreach ($expectedPairs as $ep) {
            $this->assertContains($ep, $pairs, "Pair [{$ep[0]},{$ep[1]}] missing");
        }
    }

    public function test_rr_4_players_each_player_appears_exactly_3_times()
    {
        $players = [1, 2, 3, 4];
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        $count = [];
        foreach ($matches as $m) {
            $count[$m['reg_one_id']] = ($count[$m['reg_one_id']] ?? 0) + 1;
            $count[$m['reg_two_id']] = ($count[$m['reg_two_id']] ?? 0) + 1;
        }

        foreach ($players as $p) {
            $this->assertEquals(3, $count[$p], "Player $p should appear in exactly 3 matches");
        }
    }

    public function test_rr_4_players_order_nos_are_sequential_1_to_6()
    {
        $players = [1, 2, 3, 4];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $orderNos = array_column($matches, 'order_no');
        sort($orderNos);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $orderNos);
    }

    public function test_rr_4_players_round2_returns_empty()
    {
        $this->assertEmpty($this->double->generateRoundMatches(1, 2, [1, 2, 3, 4]));
    }

    // =========================================================================
    // Round-robin — 5 players (10 matches)
    // =========================================================================

    public function test_rr_5_players_generates_10_matches()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $this->assertCount(10, $matches);
    }

    public function test_rr_5_players_all_pairs_covered()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $pairs = array_map(fn($m) => [$m['reg_one_id'], $m['reg_two_id']], $matches);

        $n = count($players);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $this->assertContains(
                    [$players[$i], $players[$j]],
                    $pairs,
                    "Pair [{$players[$i]},{$players[$j]}] missing"
                );
            }
        }
    }

    public function test_rr_5_players_each_player_appears_exactly_4_times()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        $count = [];
        foreach ($matches as $m) {
            $count[$m['reg_one_id']] = ($count[$m['reg_one_id']] ?? 0) + 1;
            $count[$m['reg_two_id']] = ($count[$m['reg_two_id']] ?? 0) + 1;
        }

        foreach ($players as $p) {
            $this->assertEquals(4, $count[$p], "Player $p should appear in exactly 4 matches");
        }
    }

    public function test_rr_5_players_order_nos_sequential_1_to_10()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $orderNos = array_column($matches, 'order_no');
        sort($orderNos);
        $this->assertEquals(range(1, 10), $orderNos);
    }

    public function test_rr_5_players_round2_returns_empty()
    {
        $this->assertEmpty($this->double->generateRoundMatches(1, 2, [1, 2, 3, 4, 5]));
    }

    // =========================================================================
    // Round-robin common properties (all sizes)
    // =========================================================================

    /**
     * @dataProvider roundRobinPlayerCountProvider
     */
    public function test_rr_all_matches_have_players_assigned($count)
    {
        $players = range(1, $count);
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        foreach ($matches as $m) {
            $this->assertNotNull($m['reg_one_id']);
            $this->assertNotNull($m['reg_two_id']);
            $this->assertNotEquals($m['reg_one_id'], $m['reg_two_id']);
        }
    }

    /**
     * @dataProvider roundRobinPlayerCountProvider
     */
    public function test_rr_no_prev_match_references($count)
    {
        $players = range(1, $count);
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        foreach ($matches as $m) {
            $this->assertNull($m['previes_mate_id1']);
            $this->assertNull($m['previes_mate_id2']);
        }
    }

    /**
     * @dataProvider roundRobinPlayerCountProvider
     */
    public function test_rr_is_double_loser_is_0_for_all_matches($count)
    {
        $players = range(1, $count);
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        foreach ($matches as $m) {
            $this->assertEquals(0, $m['is_double_loser']);
        }
    }

    /**
     * @dataProvider roundRobinPlayerCountProvider
     */
    public function test_rr_same_result_for_both_variants($count)
    {
        $players = range(1, $count);
        $a = $this->double->generateRoundMatches(1, 1, $players);
        $b = $this->singleBronze->generateRoundMatches(1, 1, $players);
        $this->assertEquals($a, $b);
    }

    public static function roundRobinPlayerCountProvider(): array
    {
        return [[3], [4], [5]];
    }

    // =========================================================================
    // Break scheduling quality
    //
    // The greedy scheduler tries to give everyone a rest break. These tests
    // verify the exact output for 4 players (deterministic with the greedy
    // algorithm) and check that the 5-player output has reasonable spacing.
    // =========================================================================

    public function test_rr_4_players_greedy_ordering_maximises_breaks()
    {
        // Greedy result for [A=1, B=2, C=3, D=4]:
        //   Slot 1: 1v2  — 3v4 have longest wait → picked next
        //   Slot 2: 3v4
        //   Slot 3: 1v3  (or 1v4 — both have min-wait 1; first pair wins)
        //   Slot 4: 2v4  (1v4 and 2v4 both min-wait 1; 2v4 has higher total → wins)
        //   Slot 5: 1v4
        //   Slot 6: 2v3
        $players = [1, 2, 3, 4];
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        // P1 should play slots 1, 3, 5 — gap ≥ 1 between each consecutive match
        $p1Slots = array_values(array_filter(
            array_map(fn($m, $i) => ($m['reg_one_id'] === 1 || $m['reg_two_id'] === 1) ? ($i + 1) : null,
                $matches, array_keys($matches)),
            fn($v) => $v !== null
        ));
        $this->assertCount(3, $p1Slots);
        $this->assertGreaterThanOrEqual(2, $p1Slots[1] - $p1Slots[0], 'P1 needs ≥1 break between matches 1&2');
        $this->assertGreaterThanOrEqual(2, $p1Slots[2] - $p1Slots[1], 'P1 needs ≥1 break between matches 2&3');
    }

    public function test_rr_5_players_each_player_gets_at_least_one_break()
    {
        $players = [1, 2, 3, 4, 5];
        $matches = $this->double->generateRoundMatches(1, 1, $players);

        foreach ($players as $p) {
            $slots = [];
            foreach ($matches as $i => $m) {
                if ($m['reg_one_id'] === $p || $m['reg_two_id'] === $p) {
                    $slots[] = $i + 1;
                }
            }

            // Check that at least one consecutive pair of matches has a gap
            $hasBreak = false;
            for ($i = 0; $i < count($slots) - 1; $i++) {
                if ($slots[$i + 1] - $slots[$i] >= 2) {
                    $hasBreak = true;
                    break;
                }
            }
            $this->assertTrue($hasBreak, "Player $p should have at least 1 match break somewhere");
        }
    }

    // =========================================================================
    // computePreviousReferences — single-round formats have no prev_refs
    // =========================================================================

    public function test_best_of_3_compute_prev_refs_no_refs()
    {
        $players = [10, 20];
        $roundData = [1 => $this->double->generateRoundMatches(1, 1, $players)];
        $result = $this->double->computePreviousReferences($roundData);

        foreach ($result[1] as $m) {
            $this->assertArrayNotHasKey('prev_refs', $m);
        }
    }

    public function test_rr_compute_prev_refs_no_refs()
    {
        $players = [1, 2, 3, 4];
        $roundData = [1 => $this->double->generateRoundMatches(1, 1, $players)];
        $result = $this->double->computePreviousReferences($roundData);

        foreach ($result[1] as $m) {
            $this->assertArrayNotHasKey('prev_refs', $m);
        }
    }

    // =========================================================================
    // determineRound labels
    // =========================================================================

    public function test_determine_round_best_of_3_match1_labelled_тулаан_1()
    {
        // bracketSize = 2 first-round matches * 2 = 4
        $this->assertEquals('ТУЛААН 1', TournamentEliminationStrategyFactory::determineRound(2, 1));
    }

    public function test_determine_round_best_of_3_match2_labelled_тулаан_2()
    {
        $this->assertEquals('ТУЛААН 2', TournamentEliminationStrategyFactory::determineRound(2, 3));
    }

    public function test_determine_round_best_of_3_match3_gold_labelled_шигшээ()
    {
        // order_no 9999 is caught by the global special-match check → ШИГШЭЭ
        $this->assertEquals('ШИГШЭЭ', TournamentEliminationStrategyFactory::determineRound(2, 9999));
    }

    public function test_determine_round_3_player_rr_labelled_бүлгийн()
    {
        // 3 matches in round 1 → bracketSize = 6
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(3, 1));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(3, 2));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(3, 3));
    }

    public function test_determine_round_4_player_rr_labelled_бүлгийн()
    {
        // 6 matches in round 1 → bracketSize = 12 (same bucket as 6-player pool)
        // order_no < 100 → 'БҮЛГИЙН ТОГЛОЛТ' (handled by existing pool logic)
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 1));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 6));
    }

    public function test_determine_round_5_player_rr_labelled_бүлгийн()
    {
        // 10 matches in round 1 → bracketSize = 20
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(10, 1));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(10, 10));
    }

    // =========================================================================
    // Boundary: 6 players still use pool format (unchanged)
    // =========================================================================

    public function test_6_players_still_uses_pool_format_3_rounds()
    {
        $this->assertEquals(3, $this->double->calculateTotalRounds(6));
    }

    public function test_6_players_round1_is_pool_roundrobin_6_matches()
    {
        $players = [1, 2, 3, 4, 5, 6];
        $matches = $this->double->generateRoundMatches(1, 1, $players);
        $this->assertCount(6, $matches);
        // All matches should have players assigned (pool round-robin)
        foreach ($matches as $m) {
            $this->assertNotNull($m['reg_one_id']);
            $this->assertNotNull($m['reg_two_id']);
        }
    }
}