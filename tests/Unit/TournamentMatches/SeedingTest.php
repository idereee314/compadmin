<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\MatchScheduler;
use event\matches\SingleEliminationStrategy;
use event\matches\DoubleEliminationStrategy;
use event\matches\MJJFEliminationStrategy;

class SeedingTest extends TestCase
{
    // -------------------------------------------------------------------------
    // generateSeededOrder — verify exact seed orderings
    // -------------------------------------------------------------------------

    public function test_seeded_order_8_players()
    {
        $order = MatchScheduler::generateSeededOrder(8);

        // Expected: 1v8, 4v5, 2v7, 3v6
        $this->assertEquals(
            [1, 8, 4, 5, 2, 7, 3, 6],
            $order
        );
    }

    public function test_seeded_order_16_players()
    {
        $order = MatchScheduler::generateSeededOrder(16);

        // Expected: 1v16, 8v9, 4v13, 5v12, 2v15, 7v10, 3v14, 6v11
        $this->assertEquals(
            [1, 16, 8, 9, 4, 13, 5, 12, 2, 15, 7, 10, 3, 14, 6, 11],
            $order
        );
    }

    public function test_seeded_order_32_players()
    {
        $order = MatchScheduler::generateSeededOrder(32);

        // Expected: 1v32, 16v17, 9v24, 8v25, 4v29, 13v20, 12v21, 5v28,
        //           2v31, 15v18, 10v23, 7v26, 3v30, 14v19, 11v22, 6v27
        $this->assertEquals(
            [1, 32, 16, 17, 9, 24, 8, 25, 4, 29, 13, 20, 12, 21, 5, 28,
             2, 31, 15, 18, 10, 23, 7, 26, 3, 30, 14, 19, 11, 22, 6, 27],
            $order
        );
    }

    public function test_seeded_order_2_players()
    {
        $this->assertEquals([1, 2], MatchScheduler::generateSeededOrder(2));
    }

    public function test_seeded_order_4_players()
    {
        // 1v4, 2v3
        $this->assertEquals([1, 4, 2, 3], MatchScheduler::generateSeededOrder(4));
    }

    // -------------------------------------------------------------------------
    // generateSeededOrder — verify matchup properties
    // -------------------------------------------------------------------------

    /**
     * Every pair sums to N+1 (seed s plays seed N+1-s)
     */
    public function test_seeded_pairs_sum_to_n_plus_1()
    {
        foreach ([8, 16, 32] as $n) {
            $order = MatchScheduler::generateSeededOrder($n);
            for ($i = 0; $i < count($order); $i += 2) {
                $this->assertEquals(
                    $n + 1,
                    $order[$i] + $order[$i + 1],
                    "Pair at position $i for bracket size $n should sum to " . ($n + 1)
                );
            }
        }
    }

    /**
     * All seeds 1..N appear exactly once
     */
    public function test_seeded_order_contains_all_seeds()
    {
        foreach ([8, 16, 32] as $n) {
            $order = MatchScheduler::generateSeededOrder($n);
            sort($order);
            $this->assertEquals(range(1, $n), $order);
        }
    }

    /**
     * If all favorites win, QF matchups for 8-man are: 1v4, 2v3
     * (seeds 1,4 in top half; seeds 2,3 in bottom half)
     */
    public function test_8_man_bracket_structure()
    {
        $order = MatchScheduler::generateSeededOrder(8);

        // Top half: matches 0,1 → QF1 feed
        // R1-M1: seed $order[0] vs $order[1] → winner = lower seed
        // R1-M2: seed $order[2] vs $order[3] → winner = lower seed
        // If favorites: QF1 = $order[0] vs $order[2]
        $this->assertEquals(1, min($order[0], $order[1]));
        $this->assertEquals(4, min($order[2], $order[3]));

        // Bottom half: matches 2,3 → QF2 feed
        $this->assertEquals(2, min($order[4], $order[5]));
        $this->assertEquals(3, min($order[6], $order[7]));
    }

    /**
     * 16-man: if favorites win, QF = 1v8, 4v5, 2v7, 3v6 (same as 8-man seeding)
     */
    public function test_16_man_bracket_qf_matchups()
    {
        $order = MatchScheduler::generateSeededOrder(16);

        // Extract favorite winners from each pair of R1 matches
        // QF1 feeds: M0+M1 (positions 0-3)
        $qf1_top = min($order[0], $order[1]);
        $qf1_bot = min($order[2], $order[3]);
        $this->assertEquals(1, $qf1_top);
        $this->assertEquals(8, $qf1_bot);

        // QF2 feeds: M2+M3 (positions 4-7)
        $qf2_top = min($order[4], $order[5]);
        $qf2_bot = min($order[6], $order[7]);
        $this->assertEquals(4, $qf2_top);
        $this->assertEquals(5, $qf2_bot);

        // QF3 feeds: M4+M5 (positions 8-11)
        $qf3_top = min($order[8], $order[9]);
        $qf3_bot = min($order[10], $order[11]);
        $this->assertEquals(2, $qf3_top);
        $this->assertEquals(7, $qf3_bot);

        // QF4 feeds: M6+M7 (positions 12-15)
        $qf4_top = min($order[12], $order[13]);
        $qf4_bot = min($order[14], $order[15]);
        $this->assertEquals(3, $qf4_top);
        $this->assertEquals(6, $qf4_bot);
    }

    /**
     * 32-man: if favorites win, QF = 1v8, 4v5, 2v7, 3v6
     */
    public function test_32_man_bracket_qf_matchups()
    {
        $order = MatchScheduler::generateSeededOrder(32);

        // Each QF is fed by 4 R1 matches (8 entries)
        // QF1 (positions 0-15): top=1, bottom=8
        $qf1Seeds = [];
        for ($i = 0; $i < 8; $i += 2) {
            $qf1Seeds[] = min($order[$i], $order[$i + 1]);
        }
        // R2 winners: positions 0-1→R2M1, 2-3→R2M2
        // QF1 = R2M1 winner vs R2M2 winner
        $this->assertEquals(1, min($qf1Seeds[0], $qf1Seeds[1]));
        $this->assertEquals(8, min($qf1Seeds[2], $qf1Seeds[3]));

        // QF2 (positions 8-15)
        $qf2Seeds = [];
        for ($i = 8; $i < 16; $i += 2) {
            $qf2Seeds[] = min($order[$i], $order[$i + 1]);
        }
        $this->assertEquals(4, min($qf2Seeds[0], $qf2Seeds[1]));
        $this->assertEquals(5, min($qf2Seeds[2], $qf2Seeds[3]));

        // QF3 (positions 16-23)
        $qf3Seeds = [];
        for ($i = 16; $i < 24; $i += 2) {
            $qf3Seeds[] = min($order[$i], $order[$i + 1]);
        }
        $this->assertEquals(2, min($qf3Seeds[0], $qf3Seeds[1]));
        $this->assertEquals(7, min($qf3Seeds[2], $qf3Seeds[3]));

        // QF4 (positions 24-31)
        $qf4Seeds = [];
        for ($i = 24; $i < 32; $i += 2) {
            $qf4Seeds[] = min($order[$i], $order[$i + 1]);
        }
        $this->assertEquals(3, min($qf4Seeds[0], $qf4Seeds[1]));
        $this->assertEquals(6, min($qf4Seeds[2], $qf4Seeds[3]));
    }

    // -------------------------------------------------------------------------
    // seedParticipants — reorder actual participant arrays
    // -------------------------------------------------------------------------

    public function test_seed_participants_8_players()
    {
        // Participants in seed order: seed 1=P1, seed 2=P2, etc.
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];
        $seeded = MatchScheduler::seedParticipants($participants);

        // Should produce pairs: P1vP8, P4vP5, P2vP7, P3vP6
        $this->assertEquals(101, $seeded[0]); // seed 1
        $this->assertEquals(108, $seeded[1]); // seed 8
        $this->assertEquals(104, $seeded[2]); // seed 4
        $this->assertEquals(105, $seeded[3]); // seed 5
        $this->assertEquals(102, $seeded[4]); // seed 2
        $this->assertEquals(107, $seeded[5]); // seed 7
        $this->assertEquals(103, $seeded[6]); // seed 3
        $this->assertEquals(106, $seeded[7]); // seed 6
    }

    public function test_seed_participants_16_players()
    {
        $participants = range(201, 216);
        $seeded = MatchScheduler::seedParticipants($participants);

        // First match: seed 1 vs seed 16
        $this->assertEquals(201, $seeded[0]);
        $this->assertEquals(216, $seeded[1]);
        // Second match: seed 8 vs seed 9
        $this->assertEquals(208, $seeded[2]);
        $this->assertEquals(209, $seeded[3]);
    }

    public function test_seed_participants_32_players()
    {
        $participants = range(301, 332);
        $seeded = MatchScheduler::seedParticipants($participants);

        // First match: seed 1 vs seed 32
        $this->assertEquals(301, $seeded[0]);
        $this->assertEquals(332, $seeded[1]);
        // Second match: seed 16 vs seed 17
        $this->assertEquals(316, $seeded[2]);
        $this->assertEquals(317, $seeded[3]);
        // Third match: seed 9 vs seed 24
        $this->assertEquals(309, $seeded[4]);
        $this->assertEquals(324, $seeded[5]);
        // Fourth match: seed 8 vs seed 25
        $this->assertEquals(308, $seeded[6]);
        $this->assertEquals(325, $seeded[7]);
    }

    public function test_seed_participants_pads_non_power_of_2()
    {
        // 9 players → 16-man bracket, 7 BYEs distributed to top seeds
        $participants = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $result = MatchScheduler::seedParticipants($participants);
        $this->assertCount(16, $result);

        // First pair: seed 1 vs BYE (seed 16 = null)
        $this->assertEquals(1, $result[0]);
        $this->assertNull($result[1]);

        // Real players and nulls correctly distributed
        $realPlayers = array_filter($result, fn($v) => $v !== null);
        $this->assertCount(9, $realPlayers);
    }

    public function test_seed_participants_seeds_small_brackets()
    {
        // 4 players → standard 4-man seeded bracket: 1v4, 2v3
        $participants = [1, 2, 3, 4];
        $result = MatchScheduler::seedParticipants($participants);
        $this->assertEquals([1, 4, 2, 3], $result);
    }

    // -------------------------------------------------------------------------
    // Strategy integration — verify seeding applied in generateRoundMatches
    // -------------------------------------------------------------------------

    public function test_single_elimination_8_players_seeded()
    {
        $strategy = new SingleEliminationStrategy();
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(4, $matches);

        // Match 1: seed 1 (101) vs seed 8 (108)
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(108, $matches[0]['reg_two_id']);

        // Match 2: seed 4 (104) vs seed 5 (105)
        $this->assertEquals(104, $matches[1]['reg_one_id']);
        $this->assertEquals(105, $matches[1]['reg_two_id']);

        // Match 3: seed 2 (102) vs seed 7 (107)
        $this->assertEquals(102, $matches[2]['reg_one_id']);
        $this->assertEquals(107, $matches[2]['reg_two_id']);

        // Match 4: seed 3 (103) vs seed 6 (106)
        $this->assertEquals(103, $matches[3]['reg_one_id']);
        $this->assertEquals(106, $matches[3]['reg_two_id']);
    }

    public function test_double_elimination_8_players_seeded()
    {
        $strategy = new DoubleEliminationStrategy();
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(4, $matches);
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(108, $matches[0]['reg_two_id']);
        $this->assertEquals(104, $matches[1]['reg_one_id']);
        $this->assertEquals(105, $matches[1]['reg_two_id']);
    }

    public function test_mjjf_elimination_8_players_seeded()
    {
        $strategy = new MJJFEliminationStrategy();
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(4, $matches);
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(108, $matches[0]['reg_two_id']);
        $this->assertEquals(104, $matches[1]['reg_one_id']);
        $this->assertEquals(105, $matches[1]['reg_two_id']);
    }

    public function test_single_elimination_16_players_seeded()
    {
        $strategy = new SingleEliminationStrategy();
        $participants = range(1, 16);
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(8, $matches);

        // Verify all 8 match pairings
        $expectedPairs = [
            [1, 16], [8, 9], [4, 13], [5, 12],
            [2, 15], [7, 10], [3, 14], [6, 11],
        ];
        foreach ($expectedPairs as $i => [$s1, $s2]) {
            $this->assertEquals($s1, $matches[$i]['reg_one_id'],
                "Match $i reg_one should be seed $s1");
            $this->assertEquals($s2, $matches[$i]['reg_two_id'],
                "Match $i reg_two should be seed $s2");
        }
    }

    public function test_single_elimination_32_players_seeded()
    {
        $strategy = new SingleEliminationStrategy();
        $participants = range(1, 32);
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(16, $matches);

        // Verify first 8 match pairings (top half)
        $expectedPairs = [
            [1, 32], [16, 17], [9, 24], [8, 25],
            [4, 29], [13, 20], [12, 21], [5, 28],
        ];
        foreach ($expectedPairs as $i => [$s1, $s2]) {
            $this->assertEquals($s1, $matches[$i]['reg_one_id'],
                "Match $i reg_one should be seed $s1");
            $this->assertEquals($s2, $matches[$i]['reg_two_id'],
                "Match $i reg_two should be seed $s2");
        }
    }

    /**
     * Seeding applies to small brackets (< 8 players)
     */
    public function test_seeding_applies_to_small_brackets()
    {
        $strategy = new SingleEliminationStrategy();
        $participants = [10, 20, 30, 40];
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        // 4-man seeded bracket: seed 1(10) vs seed 4(40), seed 2(20) vs seed 3(30)
        $this->assertCount(2, $matches);
        $this->assertEquals(10, $matches[0]['reg_one_id']);
        $this->assertEquals(40, $matches[0]['reg_two_id']);
        $this->assertEquals(20, $matches[1]['reg_one_id']);
        $this->assertEquals(30, $matches[1]['reg_two_id']);
    }

    /**
     * Non-power-of-2 brackets pad with BYEs and apply seeding
     */
    public function test_seeding_pads_odd_count()
    {
        $strategy = new SingleEliminationStrategy();
        $participants = range(1, 10);
        $matches = $strategy->generateRoundMatches(1, 1, $participants);

        // 10 players → 16-man bracket → 8 first-round matches
        $this->assertCount(8, $matches);

        // Seed 1 vs BYE (seed 16 = null)
        $this->assertEquals(1, $matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
        $this->assertEquals('C', $matches[0]['status']);

        // 10 players in 16-man bracket: 6 BYEs (seeds 11-16), 2 real matches
        $realMatches = array_filter($matches, fn($m) => $m['status'] === 'P');
        $byeMatches = array_filter($matches, fn($m) => $m['status'] === 'C');
        $this->assertCount(2, $realMatches);
        $this->assertCount(6, $byeMatches);
    }

    // -------------------------------------------------------------------------
    // BYE placement — verify seed 1 always gets BYE at top position
    // -------------------------------------------------------------------------

    /**
     * 7-man bracket: Seed 1 vs BYE must be first match (top of bracket)
     */
    public function test_7_man_bracket_seed1_bye_at_top()
    {
        $participants = range(1, 7);
        $seeded = MatchScheduler::seedParticipants($participants);

        // 7 → 8-man bracket, 1 BYE
        $this->assertCount(8, $seeded);

        // First pair (top of bracket): Seed 1 vs BYE
        $this->assertEquals(1, $seeded[0]);
        $this->assertNull($seeded[1]);

        // Verify via strategy: first match is Seed 1 vs BYE (auto-completed)
        $strategy = new SingleEliminationStrategy();
        $matches = $strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(4, $matches);
        $this->assertEquals(1, $matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
        $this->assertEquals('C', $matches[0]['status']);
    }

    /**
     * 5-man bracket: Seed 1, 2, 3 all get BYEs; Seed 1 BYE at top
     */
    public function test_5_man_bracket_bye_placement()
    {
        $participants = range(1, 5);
        $seeded = MatchScheduler::seedParticipants($participants);

        // 5 → 8-man bracket, 3 BYEs
        $this->assertCount(8, $seeded);

        // First pair: Seed 1 vs BYE (top)
        $this->assertEquals(1, $seeded[0]);
        $this->assertNull($seeded[1]);

        $strategy = new SingleEliminationStrategy();
        $matches = $strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(4, $matches);

        // 3 BYE matches, 1 real match
        $byeMatches = array_filter($matches, fn($m) => $m['status'] === 'C');
        $realMatches = array_filter($matches, fn($m) => $m['status'] === 'P');
        $this->assertCount(3, $byeMatches);
        $this->assertCount(1, $realMatches);

        // First match: Seed 1 BYE at top
        $this->assertEquals(1, $matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
    }

    /**
     * 3-man bracket: Seed 1 gets BYE at top
     */
    public function test_3_man_bracket_bye_placement()
    {
        $participants = range(1, 3);
        $seeded = MatchScheduler::seedParticipants($participants);

        // 3 → 4-man bracket
        $this->assertCount(4, $seeded);
        $this->assertEquals(1, $seeded[0]);
        $this->assertNull($seeded[1]);

        $strategy = new SingleEliminationStrategy();
        $matches = $strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(2, $matches);
        $this->assertEquals(1, $matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
        $this->assertEquals('C', $matches[0]['status']);
        // Second match: real match (Seed 2 vs Seed 3)
        $this->assertEquals(2, $matches[1]['reg_one_id']);
        $this->assertEquals(3, $matches[1]['reg_two_id']);
        $this->assertEquals('P', $matches[1]['status']);
    }

    /**
     * 6-man bracket: Seed 1 and 2 get BYEs; verify placement
     */
    public function test_6_man_bracket_bye_placement()
    {
        $participants = range(1, 6);
        $seeded = MatchScheduler::seedParticipants($participants);

        // 6 → 8-man bracket, 2 BYEs
        $this->assertCount(8, $seeded);

        // First pair: Seed 1 vs BYE (top)
        $this->assertEquals(1, $seeded[0]);
        $this->assertNull($seeded[1]);

        $strategy = new SingleEliminationStrategy();
        $matches = $strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(4, $matches);

        // 2 BYE matches, 2 real matches
        $byeMatches = array_filter($matches, fn($m) => $m['status'] === 'C');
        $realMatches = array_filter($matches, fn($m) => $m['status'] === 'P');
        $this->assertCount(2, $byeMatches);
        $this->assertCount(2, $realMatches);
    }

    /**
     * BYE placement for all non-power-of-2 sizes from 3 to 31:
     * Seed 1 must always be in the first match and get a BYE
     */
    public function test_seed1_always_gets_bye_at_top_for_non_power_of_2()
    {
        $strategy = new SingleEliminationStrategy();
        $nonPow2 = [3, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];

        foreach ($nonPow2 as $count) {
            $participants = range(1, $count);
            $matches = $strategy->generateRoundMatches(1, 1, $participants);

            // First match: Seed 1 always at top with BYE
            $this->assertEquals(1, $matches[0]['reg_one_id'],
                "For $count players, first match should have seed 1 as reg_one");
            $this->assertNull($matches[0]['reg_two_id'],
                "For $count players, first match should be a BYE (reg_two=null)");
            $this->assertEquals('C', $matches[0]['status'],
                "For $count players, first match (BYE) should be auto-completed");
        }
    }
}