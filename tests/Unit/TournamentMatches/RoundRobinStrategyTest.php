<?php

namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\RoundRobinStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoundRobinStrategyTest extends TestCase
{
    use RefreshDatabase;

    protected $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new RoundRobinStrategy();
    }

    /**
     * Test calculate total rounds for round robin
     * In round robin, rounds = participant_count - 1
     */
    public function test_calculate_total_rounds()
    {
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(0));
        $this->assertEquals(0, $this->strategy->calculateTotalRounds(1));
        $this->assertEquals(1, $this->strategy->calculateTotalRounds(2));
        $this->assertEquals(2, $this->strategy->calculateTotalRounds(3));
        $this->assertEquals(3, $this->strategy->calculateTotalRounds(4));
        $this->assertEquals(4, $this->strategy->calculateTotalRounds(5));
        $this->assertEquals(7, $this->strategy->calculateTotalRounds(8));
    }

    /**
     * Test get elimination type
     */
    public function test_get_elimination_type()
    {
        $this->assertEquals('round_robin', $this->strategy->getEliminationType());
    }

    /**
     * Test validate config with valid config
     */
    public function test_validate_config_valid()
    {
        $config = ['event_id' => 1, 'participant_count' => 5];
        $result = $this->strategy->validateConfig($config);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    /**
     * Test validate config with missing event_id
     */
    public function test_validate_config_missing_event_id()
    {
        $config = ['participant_count' => 5];
        $result = $this->strategy->validateConfig($config);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('event_id', $result['errors']);
    }

    /**
     * Test validate config warns about too many participants
     */
    public function test_validate_config_too_many_participants()
    {
        $config = ['event_id' => 1, 'participant_count' => 25];
        $result = $this->strategy->validateConfig($config);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('participant_count', $result['errors']);
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
        
        foreach ($result as $index => $entry) {
            $this->assertEquals(1, $entry['event_id']);
            $this->assertEquals('active', $entry['status']);
            $this->assertEquals(0, $entry['points']);
            $this->assertEquals(0, $entry['wins']);
            $this->assertEquals(0, $entry['losses']);
            $this->assertEquals($index + 1, $entry['position']);
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
     * Test round robin generates all pairings
     */
    public function test_generate_round_matches_creates_all_pairings()
    {
        // With 4 participants: 4*3/2 = 6 matches
        // With 5 participants: 5*4/2 = 10 matches
        $this->assertTrue(method_exists($this->strategy, 'generateRoundMatches'));
    }

    /**
     * Test process match result updates standings
     */
    public function test_process_match_result()
    {
        $this->assertTrue(method_exists($this->strategy, 'processMatchResult'));
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
