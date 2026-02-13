<?php

namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\TournamentMatchService;
use event\matches\TournamentEliminationStrategyFactory;
use event\matches\SingleEliminationStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentMatchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new TournamentEliminationStrategyFactory();
        $this->service = new TournamentMatchService($this->factory);
    }

    /**
     * Test service initialization
     */
    public function test_service_initialization()
    {
        $this->assertInstanceOf(TournamentMatchService::class, $this->service);
    }

    /**
     * Test service with default factory
     */
    public function test_service_with_default_factory()
    {
        $service = new TournamentMatchService();
        $this->assertInstanceOf(TournamentMatchService::class, $service);
    }

    /**
     * Test get available elimination types
     */
    public function test_get_available_elimination_types()
    {
        $types = $this->service->getAvailableEliminationTypes();
        
        $this->assertIsArray($types);
        $this->assertContains('single_elimination', $types);
        $this->assertContains('double_elimination', $types);
        $this->assertContains('round_robin', $types);
    }

    /**
     * Test initialize tournament with invalid elimination type
     */
    public function test_initialize_tournament_invalid_type()
    {
        $result = $this->service->initializeTournament(
            1,
            'invalid_type',
            [1, 2, 3, 4],
            ['event_id' => 1]
        );
        
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid elimination type', $result['error']);
    }

    /**
     * Test initialize tournament interface methods exist
     */
    public function test_tournament_service_interface_methods()
    {
        $this->assertTrue(method_exists($this->service, 'initializeTournament'));
        $this->assertTrue(method_exists($this->service, 'recordMatchResult'));
        $this->assertTrue(method_exists($this->service, 'getTournamentStandings'));
        $this->assertTrue(method_exists($this->service, 'getTournamentFinal'));
        $this->assertTrue(method_exists($this->service, 'getAvailableEliminationTypes'));
    }

    /**
     * Test record match result with invalid match
     */
    public function test_record_match_result_invalid_match()
    {
        $result = $this->service->recordMatchResult(999, 1, []);
        
        $this->assertFalse($result['success']);
        $this->assertEquals('Match not found', $result['error']);
    }

    /**
     * Test get tournament standings returns array
     */
    public function test_get_tournament_standings()
    {
        $standings = $this->service->getTournamentStandings(999);
        $this->assertIsArray($standings);
    }

    /**
     * Test get tournament final
     */
    public function test_get_tournament_final()
    {
        $final = $this->service->getTournamentFinal(999);
        $this->assertNull($final);
    }
}
