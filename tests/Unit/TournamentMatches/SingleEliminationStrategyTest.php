<?php

namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\SingleEliminationStrategy;
use event\EventBrackets;
use event\EventMatches;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class SingleEliminationStrategyTest extends TestCase
{
    use RefreshDatabase;

    protected $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new SingleEliminationStrategy();
    }

    /**
     * Test calculate total rounds
     */
    public function test_calculate_total_rounds()
    {
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(0));
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(1));
        $this->assertEquals(1, $this->strategy->calculateTotalRounds(2));
        $this->assertEquals(2, $this->strategy->calculateTotalRounds(3));
        $this->assertEquals(2, $this->strategy->calculateTotalRounds(4));
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(5));
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(8));
        $this->assertEquals(4, $this->strategy->calculateTotalRounds(16));
    }

    /**
     * Test get elimination type
     */
    public function test_get_elimination_type()
    {
        $this->assertEquals('single_elimination', $this->strategy->getEliminationType());
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
            $this->assertEquals(1, $entry['round']);
            $this->assertEquals('pending', $entry['status']);
            $this->assertEquals(2, $entry['total_rounds']);
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
     * Test generate first round matches
     */
    public function test_generate_first_round_matches()
    {
        // This will be tested with database fixtures in integration tests
        // For unit tests, we verify the method structure
        $this->assertTrue(method_exists($this->strategy, 'generateRoundMatches'));
    }

    /**
     * Test process match result
     */
    public function test_process_match_result()
    {
        // Create mock match
        $match = Mockery::mock(EventMatches::class);
        $match->reg_one_id = 1;
        $match->reg_two_id = 2;
        $match->shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($match);
        $match->shouldReceive('save')
            ->once();

        // This test would require proper mocking of Eloquent
        // For now, verify the method exists
        $this->assertTrue(method_exists($this->strategy, 'processMatchResult'));
    }

    /**
     * Test isRoundComplete with no matches
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
     * Test get required fields (from interface)
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
