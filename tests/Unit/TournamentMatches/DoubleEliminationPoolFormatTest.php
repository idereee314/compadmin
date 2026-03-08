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

    /**
     * Test that 6 players triggers pool format with 3 rounds
     */
    public function test_calculate_total_rounds_for_6_players()
    {
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(6));
        $this->assertEquals(3, $this->singleBronzeStrategy->calculateTotalRounds(6));
    }

    /**
     * Test that 7+ players does NOT use pool format
     */
    public function test_calculate_total_rounds_for_7_plus_players()
    {
        // 7 players should NOT use pool format (ceil(log2(7)) = 3)
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(7));
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(8));
    }

    /**
     * Test round 1: pool matches generate 6 round-robin matches for 6 players
     */
    public function test_pool_format_round1_generates_6_matches()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        $this->assertCount(6, $matches);

        // All matches should be winners bracket (is_double_loser = 0)
        foreach ($matches as $match) {
            $this->assertEquals(0, $match['is_double_loser']);
            $this->assertEquals('P', $match['status']);
            $this->assertEquals(1, $match['event_id']);
        }
    }

    /**
     * Test round 1: Pool 1 has correct round-robin pairings
     */
    public function test_pool_format_round1_pool1_pairings()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        // Pool 1: players 101, 102, 103 → matches: 101v102, 101v103, 102v103
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(102, $matches[0]['reg_two_id']);

        $this->assertEquals(101, $matches[1]['reg_one_id']);
        $this->assertEquals(103, $matches[1]['reg_two_id']);

        $this->assertEquals(102, $matches[2]['reg_one_id']);
        $this->assertEquals(103, $matches[2]['reg_two_id']);
    }

    /**
     * Test round 1: Pool 2 has correct round-robin pairings
     */
    public function test_pool_format_round1_pool2_pairings()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        // Pool 2: players 104, 105, 106 → matches: 104v105, 104v106, 105v106
        $this->assertEquals(104, $matches[3]['reg_one_id']);
        $this->assertEquals(105, $matches[3]['reg_two_id']);

        $this->assertEquals(104, $matches[4]['reg_one_id']);
        $this->assertEquals(106, $matches[4]['reg_two_id']);

        $this->assertEquals(105, $matches[5]['reg_one_id']);
        $this->assertEquals(106, $matches[5]['reg_two_id']);
    }

    /**
     * Test round 1: order_no is sequential 1-6
     */
    public function test_pool_format_round1_order_numbers()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        for ($i = 0; $i < 6; $i++) {
            $this->assertEquals($i + 1, $matches[$i]['order_no']);
        }
    }

    /**
     * Test round 2: semi-finals generate 2 matches
     */
    public function test_pool_format_round2_generates_2_semifinals()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 2, $participants);

        $this->assertCount(2, $matches);

        // Semi-finals have no players assigned (determined by pool standings)
        $this->assertNull($matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);
        $this->assertNull($matches[1]['reg_one_id']);
        $this->assertNull($matches[1]['reg_two_id']);

        // Both are winners bracket
        $this->assertEquals(0, $matches[0]['is_double_loser']);
        $this->assertEquals(0, $matches[1]['is_double_loser']);

        // order_no: 201, 202
        $this->assertEquals(201, $matches[0]['order_no']);
        $this->assertEquals(202, $matches[1]['order_no']);
    }

    /**
     * Test round 3: final (Gold) + bronze match
     */
    public function test_pool_format_round3_generates_final_and_bronze()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 3, $participants);

        $this->assertCount(2, $matches);

        // Gold match
        $this->assertEquals(9999, $matches[0]['order_no']);
        $this->assertEquals(0, $matches[0]['is_double_loser']);
        $this->assertNull($matches[0]['reg_one_id']);
        $this->assertNull($matches[0]['reg_two_id']);

        // Bronze match
        $this->assertEquals(9998, $matches[1]['order_no']);
        $this->assertEquals(1, $matches[1]['is_double_loser']);
        $this->assertNull($matches[1]['reg_one_id']);
        $this->assertNull($matches[1]['reg_two_id']);
    }

    /**
     * Test that pool format works the same for double_single_bronze variant
     */
    public function test_pool_format_works_for_single_bronze_variant()
    {
        $participants = [101, 102, 103, 104, 105, 106];

        $round1 = $this->singleBronzeStrategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(6, $round1);

        $round2 = $this->singleBronzeStrategy->generateRoundMatches(1, 2, $participants);
        $this->assertCount(2, $round2);

        $round3 = $this->singleBronzeStrategy->generateRoundMatches(1, 3, $participants);
        $this->assertCount(2, $round3);
        $this->assertEquals(9999, $round3[0]['order_no']);
        $this->assertEquals(9998, $round3[1]['order_no']);
    }

    /**
     * Test total match count: 6 pool + 2 SF + 1 Gold + 1 Bronze = 10
     */
    public function test_pool_format_total_match_count()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $totalRounds = $this->strategy->calculateTotalRounds(6);
        $totalMatches = 0;

        for ($round = 1; $round <= $totalRounds; $round++) {
            $matches = $this->strategy->generateRoundMatches(1, $round, $participants);
            $totalMatches += count($matches);
        }

        $this->assertEquals(10, $totalMatches);
    }

    /**
     * Test computePreviousReferences for pool format
     */
    public function test_pool_format_previous_references()
    {
        $participants = [101, 102, 103, 104, 105, 106];

        // Generate all rounds
        $roundMatchesData = [];
        $totalRounds = $this->strategy->calculateTotalRounds(6);
        for ($round = 1; $round <= $totalRounds; $round++) {
            $roundMatchesData[$round] = $this->strategy->generateRoundMatches(1, $round, $participants);
        }

        // Compute prev refs
        $result = $this->strategy->computePreviousReferences($roundMatchesData);

        // Round 1 (pool matches): no prev_refs
        foreach ($result[1] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }

        // Round 2 (semi-finals): no prev_refs
        foreach ($result[2] as $match) {
            $this->assertArrayNotHasKey('prev_refs', $match);
        }

        // Round 3 (final + bronze): both link to semi-finals
        foreach ($result[3] as $match) {
            $this->assertArrayHasKey('prev_refs', $match);
            $this->assertEquals(['round' => 2, 'index' => 0], $match['prev_refs']['p1']);
            $this->assertEquals(['round' => 2, 'index' => 1], $match['prev_refs']['p2']);
        }
    }

    /**
     * Test that 7 players still use standard double elimination (not pool format)
     */
    public function test_7_players_uses_standard_bracket()
    {
        $participants = [101, 102, 103, 104, 105, 106, 107];

        // Round 1 should be standard first-round matches (floor(7/2) = 3 matches)
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(3, $matches);

        // Verify it's standard pairing, not round-robin pools
        $this->assertEquals(101, $matches[0]['reg_one_id']);
        $this->assertEquals(102, $matches[0]['reg_two_id']);
        $this->assertEquals(103, $matches[1]['reg_one_id']);
        $this->assertEquals(104, $matches[1]['reg_two_id']);
        $this->assertEquals(105, $matches[2]['reg_one_id']);
        $this->assertEquals(106, $matches[2]['reg_two_id']);
    }

    /**
     * Test that 8 players still use standard double elimination
     */
    public function test_8_players_uses_standard_bracket()
    {
        $participants = [101, 102, 103, 104, 105, 106, 107, 108];

        // Round 1: 4 matches (standard pairing)
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);
        $this->assertCount(4, $matches);
    }

    /**
     * Test determineRound for pool format bracket (bracketSize = 12)
     */
    public function test_determine_round_pool_format_pool_match()
    {
        // 6 first-round pool matches → totalMatches = 6
        // Pool matches (order_no 1-6)
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 1));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 3));
        $this->assertEquals('БҮЛГИЙН ТОГЛОЛТ', TournamentEliminationStrategyFactory::determineRound(6, 6));
    }

    /**
     * Test determineRound for pool format semi-finals
     */
    public function test_determine_round_pool_format_semifinal()
    {
        // Semi-finals (order_no 201, 202)
        $this->assertEquals('ХАГАС ШИГШЭЭ (SF)', TournamentEliminationStrategyFactory::determineRound(6, 201));
        $this->assertEquals('ХАГАС ШИГШЭЭ (SF)', TournamentEliminationStrategyFactory::determineRound(6, 202));
    }

    /**
     * Test determineRound for pool format final and bronze
     */
    public function test_determine_round_pool_format_final_and_bronze()
    {
        // Final (order_no 9999)
        $this->assertEquals('ШИГШЭЭ', TournamentEliminationStrategyFactory::determineRound(6, 9999));

        // Bronze (order_no 9998)
        $this->assertEquals('ХҮРЭЛ МЕДАЛЬ', TournamentEliminationStrategyFactory::determineRound(6, 9998));
    }

    /**
     * Test round 1 pool matches have both players assigned
     */
    public function test_pool_matches_have_players_assigned()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        // All 6 pool matches should have both reg_one_id and reg_two_id set
        foreach ($matches as $match) {
            $this->assertNotNull($match['reg_one_id']);
            $this->assertNotNull($match['reg_two_id']);
        }
    }

    /**
     * Test that round 4 returns empty for pool format (only 3 rounds)
     */
    public function test_pool_format_round4_returns_empty()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 4, $participants);

        $this->assertEmpty($matches);
    }

    /**
     * Test pool match structure has previes_mate_id fields set to null
     */
    public function test_pool_matches_have_null_prev_match_ids()
    {
        $participants = [101, 102, 103, 104, 105, 106];
        $matches = $this->strategy->generateRoundMatches(1, 1, $participants);

        foreach ($matches as $match) {
            $this->assertNull($match['previes_mate_id1']);
            $this->assertNull($match['previes_mate_id2']);
        }
    }
}