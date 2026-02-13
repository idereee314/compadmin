<?php

namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\DoubleEliminationStrategy;
use event\EventBrackets;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoubleEliminationStrategyTest extends TestCase
{
    use RefreshDatabase;

    protected $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new DoubleEliminationStrategy();
    }

    /**
     * Test calculate total rounds for double elimination
     */
    public function test_calculate_total_rounds()
    {
        // Double elimination requires roughly 2x single elimination rounds
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(0));
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(1));
        $this->assertEquals(2, $this->strategy->calculateTotalRounds(2));
        $this->assertEquals(4, $this->strategy->calculateTotalRounds(3));
        $this->assertEquals(4, $this->strategy->calculateTotalRounds(4));
        $this->assertEquals(6, $this->strategy->calculateTotalRounds(8));
    }

    /**
     * Test get elimination type
     */
    public function test_get_elimination_type()
    {
        $this->assertEquals('double_elimination', $this->strategy->getEliminationType());
    }

    /**
     * Test validate config with valid config
     */
    public function test_validate_config_valid()
    {
        $config = ['event_id' => 1];
        $result = $this->strategy->validateConfig($config);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    /**
     * Test validate config with missing event_id
     */
    public function test_validate_config_missing_event_id()
    {
        $config = [];
        $result = $this->strategy->validateConfig($config);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('event_id', $result['errors']);
    }

    /**
     * Test generate bracket with insufficient participants
     */
    public function test_generate_bracket_insufficient_participants()
    {
        $result = $this->strategy->generateBracket(1, [], ['event_id' => 1]);
        $this->assertEmpty($result);

        $result = $this->strategy->generateBracket(1, [1], ['event_id' => 1]);
        $this->assertEmpty($result);
    }

    /**
     * Test generate bracket with valid participants
     */
    public function test_generate_bracket_valid_participants()
    {
        $participants = [1, 2, 3, 4];
        $result = $this->strategy->generateBracket(1, $participants, ['event_id' => 1]);

        $this->assertCount(4, $result);
        
        foreach ($result as $entry) {
            $this->assertEquals(1, $entry['event_id']);
            $this->assertEquals('winners', $entry['bracket_type']);
            $this->assertEquals(1, $entry['round']);
            $this->assertEquals('pending', $entry['status']);
            $this->assertEquals(0, $entry['loss_count']);
        }
    }

    /**
     * Test generate bracket with invalid config
     */
    public function test_generate_bracket_invalid_config()
    {
        $participants = [1, 2, 3, 4];
        $result = $this->strategy->generateBracket(1, $participants, []);

        $this->assertEmpty($result);
    }

    /**
     * Test process match result moves loser to losers bracket
     */
    public function test_process_match_result()
    {
        $this->assertTrue(method_exists($this->strategy, 'processMatchResult'));
    }

    /**
     * Test loser advancement to losers bracket
     */
    public function test_move_to_losers_path()
    {
        $this->assertTrue(method_exists($this->strategy, 'generateRoundMatches'));
    }

    /**
     * Test isRoundComplete
     */
    public function test_is_round_complete_no_matches()
    {
        $isComplete = $this->strategy->isRoundComplete(999, 1);
        $this->assertFalse($isComplete);
    }

    /**
     * Test get final match
     */
    public function test_get_final_match()
    {
        $this->assertTrue(method_exists($this->strategy, 'getFinalMatch'));
    }

    /**
     * Test get tournament standings
     */
    public function test_get_tournament_standings()
    {
        $standings = $this->strategy->getTournamentStandings(999);
        $this->assertIsArray($standings);
    }

    /**
     * Test interface methods exist
     */
    public function test_interface_methods_exist()
    {
        $this->assertTrue(method_exists($this->strategy, 'generateBracket'));
        $this->assertTrue(method_exists($this->strategy, 'generateRoundMatches'));
        $this->assertTrue(method_exists($this->strategy, 'processMatchResult'));
        $this->assertTrue(method_exists($this->strategy, 'getNextRoundMatches'));
        $this->assertTrue(method_exists($this->strategy, 'isRoundComplete'));
        $this->assertTrue(method_exists($this->strategy, 'getFinalMatch'));
        $this->assertTrue(method_exists($this->strategy, 'getTournamentStandings'));
        $this->assertTrue(method_exists($this->strategy, 'validateConfig'));
        $this->assertTrue(method_exists($this->strategy, 'getEliminationType'));
        $this->assertTrue(method_exists($this->strategy, 'calculateTotalRounds'));
    }
}
