<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\DoubleEliminationStrategy;
use event\matches\TournamentEliminationStrategyFactory;

class DoubleEliminationPoolFormatTest extends TestCase
{
    protected $strategy;
    protected $singleBronzeStrategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new DoubleEliminationStrategy('double');
        $this->singleBronzeStrategy = new DoubleEliminationStrategy('double_single_bronze');
    }

    // -------------------------------------------------------------------------
    // calculateTotalRounds
    // -------------------------------------------------------------------------

    public function test_calculate_total_rounds_for_6_players()
    {
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(6));
        $this->assertEquals(3, $this->singleBronzeStrategy->calculateTotalRounds(6));
    }

    public function test_calculate_total_rounds_for_7_plus_players_not_pool()
    {
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(7));
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(8));
    }

    // -------------------------------------------------------------------------
    // Round 1 — pool round-robin matches
    // -------------------------------------------------------------------------

    public function test_pool_format_round1_generates_6_matches()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(6, $matches);

        foreach ($matches as $match) {
            $this->assertEquals(0, $match['is_double_loser']);
            $this->assertEquals('P', $match['status']);
            $this->assertEquals(1, $match['event_id']);
        }
    }

    public function test_pool_format_round1_pool1_pairings()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        // Pool 1 (101, 102, 103): 101v102, 101v103, 102v103
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(102, $matches[0]['reg_two_id']);

        $this->assertEquals(101, $matches[1]['reg_one_id']);
        $this->assertEquals(103, $matches[1]['reg_two_id']);

        $this->assertEquals(102, $matches[2]['reg_one_id']);
        $this->assertEquals(103, $matches[2]['reg_two_id']);
    }

    public function test_pool_format_round1_pool2_pairings()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        // Pool 2 (104, 105, 106): 104v105, 104v106, 105v106
        $this->assertEquals(104, $matches[3]['reg_one_id']);
        $this->assertEquals(105, $matches[3]['reg_two_id']);

        $this->assertEquals(104, $matches[4]['reg_one_id']);
        $this->assertEquals(106, $matches[4]['reg_two_id']);

        $this->assertEquals(105, $matches[5]['reg_one_id']);
        $this->assertEquals(106, $matches[5]['reg_two_id']);
    }

    public function test_pool_format_round1_order_numbers_are_sequential()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        for ($i = 0; $i < 6; $i++) {
            $this->assertEquals($i + 1, $matches[$i]['order_no']);
        }
    }

    public function test_pool_matches_have_both_players_assigned()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        foreach ($matches as $match) {
            $this->assertNotNull($match['reg_one_id']);
            $this->assertNotNull($match['reg_two_id']);
        }
    }

    public function test_pool_matches_have_null_prev_match_ids()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        foreach ($matches as $match) {
            $this->assertNull($match['previes_mate_id1']);
            $this->assertNull($match['previes_mate_id2']);
        }
    }

    // -------------------------------------------------------------------------
    // Round 2 — semi-finals (identical for both variants)
    // -------------------------------------------------------------------------

    public function test_pool_format_round2_generates_2_semifinals()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        
        foreach ([$this->strategy, $this->singleBronzeStrategy] as $strat) {
            $matches = $strat->generateRoundMatches(1, 2, $participants);

            $this->assertCount(2, $matches);
            $this->assertNull($matches[0]['reg_one_id']);
            $this->assertNull($matches[0]['reg_two_id']);
            $this->assertNull($matches[1]['reg_one_id']);
            $this->assertNull($matches[1]['reg_two_id']);

            $this->assertEquals(0, $matches[0]['is_double_loser']);
            $this->assertEquals(0, $matches[1]['is_double_loser']);

            $this->assertEquals(201, $matches[0]['order_no']);
            $this->assertEquals(202, $matches[1]['order_no']);
        }
    }

    // -------------------------------------------------------------------------
    // Round 3 — 'double' variant: Gold only (SF losers get bronze automatically)
    // -------------------------------------------------------------------------

    public function test_pool_format_round3_double_generates_gold_only()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 3, $participants);

        $this->assertCount(1, $matches);
        $this->assertEquals(9999, $matches[0]['order_no']);
        $this->assertEquals(0, $matches[0]['is_double_loser']);
        $this->assertNull($matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
    }

    // -------------------------------------------------------------------------
    // Round 3 — 'double_single_bronze': Bronze first, then Gold
    // -------------------------------------------------------------------------

    public function test_pool_format_round3_single_bronze_generates_bronze_then_gold()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->singleBronzeStrategy->generateRoundMatches(1, 3, $participants);

        $this->assertCount(2, $matches);

        // Bronze first — Loser M7 vs Loser M8
        $this->assertEquals(9998, $matches[0]['order_no']);
        $this->assertEquals(1, $matches[0]['is_double_loser']);
        $this->assertNull($matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);

        // Gold last
        $this->assertEquals(9999, $matches[1]['order_no']);
        $this->assertEquals(0, $matches[1]['is_double_loser']);
        $this->assertNull($matches[1]['reg_one_id']);
        $this->assertNull($matches[1]['reg_two_id']);
    }

    public function test_pool_round3_gold_always_has_highest_order_no()
    {
        $participants = [101, 102, 103, 104, 105, 106];

        foreach ([$this->strategy, $this->singleBronzeStrategy] as $strat) {
            $matches = $strat->generateRoundMatches(1, 3, $participants);
            $orderNos = array_column($matches, 'order_no');
            $this->assertEquals(9999, max($orderNos), 'Gold must always have the highest order_no');
        }
    }

    // -------------------------------------------------------------------------
    // Total match counts
    //   'double'               — 9 matches  (6 pool + 2 SF + 1 Gold)
    //   'double_single_bronze' — 10 matches (6 pool + 2 SF + 1 Bronze + 1 Gold)
    // -------------------------------------------------------------------------

    public function test_double_pool_total_match_count_is_9()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $total = 0;

        for ($round = 1; $round <= $this->strategy->calculateTotalRounds(6); $round++) {
            $total += count($this->strategy->generateRoundMatches(1, $round, $participants));
        }

       $this->assertEquals(9, $total);
    }

    public function test_single_bronze_pool_total_match_count_is_10()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $total = 0;

        for ($round = 1; $round <= $this->singleBronzeStrategy->calculateTotalRounds(6); $round++) {
            $total += count($this->singleBronzeStrategy->generateRoundMatches(1, $round, $participants));
        }

        $this->assertEquals(10, $total);
    }

    // -------------------------------------------------------------------------
    // computePreviousReferences
    // -------------------------------------------------------------------------

    public function test_pool_format_prev_refs_double_variant()
    {
        $participants = [101, 102, 103, 104, 105, 106];

        $roundMatchesData = [];
       for ($round = 1; $round <= $this->strategy->calculateTotalRounds(6); $round++) {
            $roundMatchesData[$round] = $this->strategy->generateRoundMatches(1, $round, $participants);
        }

        $result = $this->strategy->computePreviousReferences($roundMatchesData);

        // Round 1: no prev_refs
        foreach ($result[1] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }

        // Round 2: no prev_refs (filled from pool standings)
        foreach ($result[2] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }

        // Round 3: Gold only — links to both semi-finals
        $this->assertCount(1, $result[3]);
        $this->assertArrayHasKey('prev_refs', $result[3][0]);
        $this->assertEquals(['round' => 2, 'index' => 0], $result[3][0]['prev_refs']['p1']);
        $this->assertEquals(['round' => 2, 'index' => 1], $result[3][0]['prev_refs']['p2']);
    }

    public function test_pool_format_prev_refs_single_bronze_variant()
    {
        $participants = [101, 102, 103, 104, 105, 106];

        $roundMatchesData = [];
        for ($round = 1; $round <= $this->singleBronzeStrategy->calculateTotalRounds(6); $round++) {
            $roundMatchesData[$round] = $this->singleBronzeStrategy->generateRoundMatches(1, $round, $participants);
        }

        $result = $this->singleBronzeStrategy->computePreviousReferences($roundMatchesData);

        foreach ($result[1] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }
        foreach ($result[2] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }

        // Round 3: Bronze (idx=0) and Gold (idx=1) both link to both semi-finals
        $this->assertCount(2, $result[3]);
        foreach ($result[3] as $match) {
            $this->assertArrayHasKey('prev_refs', $match);
            $this->assertEquals(['round' => 2, 'index' => 0], $match['prev_refs']['p1']);
            $this->assertEquals(['round' => 2, 'index' => 1], $match['prev_refs']['p2']);
        }
    }

    // -------------------------------------------------------------------------
    // 7+ players must keep standard double-elimination logic
    // -------------------------------------------------------------------------

    public function test_7_players_uses_standard_bracket_pairing()
    {
        $participants = [101, 102, 103, 104, 105, 106, 107];

        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(3, $matches);

        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(102, $matches[0]['reg_two_id']);
        $this->assertEquals(103, $matches[1]['reg_one_id']);
        $this->assertEquals(104, $matches[1]['reg_two_id']);
        $this->assertEquals(105, $matches[2]['reg_one_id']);
        $this->assertEquals(106, $matches[2]['reg_two_id']);
    }

    public function test_8_players_uses_standard_bracket()
    {
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(4, $matches);
    }

    public function test_pool_format_round4_returns_empty()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $this->assertEmpty($this->strategy->generateRoundMatches(1, 4, $participants));
        $this->assertEmpty($this->singleBronzeStrategy->generateRoundMatches(1, 4, $participants));
    }

    // -------------------------------------------------------------------------
    // determineRound — pool bracket naming
    // -------------------------------------------------------------------------

    public function test_determine_round_pool_matches_labelled_correctly()
    {
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 1));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 3));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 6));
    }

    public function test_determine_round_pool_semifinals_labelled_correctly()
    {
        $this->assertEquals('ХАГАС ШИГШЭЭ (SF)', TournamentEliminationStrategyFactory::determineRound(6, 201));
        $this->assertEquals('ХАГАС ШИГШЭЭ (SF)', TournamentEliminationStrategyFactory::determineRound(6, 202));
    }

    public function test_determine_round_gold_labelled_correctly()
    {
        $this->assertEquals('ШИГШЭЭ', TournamentEliminationStrategyFactory::determineRound(6, 9999));
    }

    public function test_determine_round_bronze_labelled_correctly()
    {
        $this->assertEquals('ХҮРЭЛ МЕДАЛЬ', TournamentEliminationStrategyFactory::determineRound(6, 9998));
    }
}