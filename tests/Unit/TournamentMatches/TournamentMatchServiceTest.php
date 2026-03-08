<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\TournamentMatchService;
use event\matches\TournamentEliminationStrategyFactory;

class TournamentMatchServiceTest extends TestCase
{
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
        $this->assertContains('single', $types);
        $this->assertContains('double', $types);
        $this->assertContains('double_single_bronze', $types);
        $this->assertContains('mjjf', $types);
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
}