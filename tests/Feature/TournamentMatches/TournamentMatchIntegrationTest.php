<?php

namespace Tests\Feature\TournamentMatches;

use Tests\TestCase;
use event\matches\TournamentMatchService;
use event\matches\SingleEliminationStrategy;
use event\EventBrackets;
use event\EventMatches;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentMatchIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TournamentMatchService();
    }

    /**
     * Test complete single elimination tournament flow
     */
    public function test_single_elimination_tournament_flow()
    {
        // Create test participants
        $participants = [1, 2, 3, 4];

        // Initialize tournament
        $result = $this->service->initializeTournament(
            eventId: 1,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 1]
        );

        // Verify initialization - all rounds are generated at once
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['event_id']);
        $this->assertEquals('single_elimination', $result['elimination_type']);
        $this->assertEquals(2, $result['total_rounds']);
        // 4 participants = 2 matches round 1 + 1 match round 2 = 3 total matches
        $this->assertEquals(3, $result['total_matches']);
        $this->assertEquals(4, $result['participant_count']);
    }

    /**
     * Test double elimination tournament initialization
     */
    public function test_double_elimination_tournament_initialization()
    {
        $participants = [1, 2, 3, 4];

        $result = $this->service->initializeTournament(
            eventId: 2,
            eliminationType: 'double_elimination',
            participants: $participants,
            config: ['event_id' => 2]
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['event_id']);
        $this->assertEquals('double_elimination', $result['elimination_type']);
    }

    /**
     * Test round robin tournament initialization
     */
    public function test_round_robin_tournament_initialization()
    {
        $participants = [1, 2, 3, 4];

        $result = $this->service->initializeTournament(
            eventId: 3,
            eliminationType: 'round_robin',
            participants: $participants,
            config: ['event_id' => 3]
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['event_id']);
        $this->assertEquals('round_robin', $result['elimination_type']);
        // Round robin with 4 participants: 4*3/2 = 6 matches
        $this->assertEquals(6, $result['total_matches']);
        $this->assertEquals(4, $result['bracket_positions']);
    }

    /**
     * Test bracket creation
     */
    public function test_bracket_creation()
    {
        $participants = [1, 2, 3, 4];

        $result = $this->service->initializeTournament(
            eventId: 4,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 4]
        );

        $eventId = $result['event_id'];
        
        // Verify participant count matches
        $this->assertEquals(4, $result['participant_count']);
    }

    /**
     * Test initial matches are created
     */
    public function test_initial_matches_creation()
    {
        $participants = [1, 2, 3, 4];

        $result = $this->service->initializeTournament(
            eventId: 5,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 5]
        );

        $eventId = $result['event_id'];

        // Verify all rounds are created with proper match count
        // Round 1: 2 matches, Round 2: 1 match = 3 total matches
        $totalMatches = EventMatches::where('event_id', $eventId)->count();
        $this->assertEquals(3, $totalMatches);

        // Verify match structure - check that matches are pending
        $matches = EventMatches::where('event_id', $eventId)->get();
        foreach ($matches as $match) {
            $this->assertEquals($eventId, $match->event_id);
            $this->assertEquals('pending', $match->status);
        }    }

    /**
     * Test tournament with minimum participants
     */
    public function test_tournament_minimum_participants()
    {
        $participants = [1, 2];

        $result = $this->service->initializeTournament(
            eventId: 6,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 6]
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['total_rounds']);
        $this->assertEquals(1, $result['total_matches']);
    }

    /**
     * Test tournament with 8 participants
     */
    public function test_tournament_eight_participants()
    {
        $participants = [1, 2, 3, 4, 5, 6, 7, 8];

        $result = $this->service->initializeTournament(
            eventId: 7,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 7]
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['total_rounds']);
        // 8 participants = 4 + 2 + 1 = 7 total matches
        $this->assertEquals(7, $result['total_matches']);
    }

    /**
     * Test get available elimination types
     */
    public function test_get_available_elimination_types()
    {
        $types = $this->service->getAvailableEliminationTypes();

        $this->assertIsArray($types);
        $this->assertCount(3, $types);
        $this->assertContains('single_elimination', $types);
        $this->assertContains('double_elimination', $types);
        $this->assertContains('round_robin', $types);
    }

    /**
     * Test invalid tournament configuration
     */
    public function test_invalid_tournament_configuration()
    {
        // Single elimination requires at least 2 participants
        $result = $this->service->initializeTournament(
            eventId: 8,
            eliminationType: 'single_elimination',
            participants: [1],
            config: ['event_id' => 8]
        );

        $this->assertFalse($result['success']);
    }

    /**
     * Test tournament with odd number of participants
     */
    public function test_tournament_odd_participants()
    {
        $participants = [1, 2, 3, 4, 5];

        $result = $this->service->initializeTournament(
            eventId: 9,
            eliminationType: 'single_elimination',
            participants: $participants,
            config: ['event_id' => 9]
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['total_rounds']);
        // 5 participants: all rounds generated
        $this->assertGreaterThanOrEqual(3, $result['total_matches']);
    }
}
