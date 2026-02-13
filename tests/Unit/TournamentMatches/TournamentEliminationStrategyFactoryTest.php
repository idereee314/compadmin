<?php

namespace Tests\Unit\TournamentMatches;

use Tests\TestCase;
use event\matches\TournamentEliminationStrategyFactory;
use event\matches\SingleEliminationStrategy;
use event\matches\DoubleEliminationStrategy;
use event\matches\RoundRobinStrategy;

class TournamentEliminationStrategyFactoryTest extends TestCase
{
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new TournamentEliminationStrategyFactory();
    }

    /**
     * Test factory creates single elimination strategy
     */
    public function test_create_single_elimination_strategy()
    {
        $strategy = $this->factory->createStrategy('single_elimination');
        
        $this->assertInstanceOf(SingleEliminationStrategy::class, $strategy);
        $this->assertEquals('single_elimination', $strategy->getEliminationType());
    }

    /**
     * Test factory creates double elimination strategy
     */
    public function test_create_double_elimination_strategy()
    {
        $strategy = $this->factory->createStrategy('double_elimination');
        
        $this->assertInstanceOf(DoubleEliminationStrategy::class, $strategy);
        $this->assertEquals('double_elimination', $strategy->getEliminationType());
    }

    /**
     * Test factory creates round robin strategy
     */
    public function test_create_round_robin_strategy()
    {
        $strategy = $this->factory->createStrategy('round_robin');
        
        $this->assertInstanceOf(RoundRobinStrategy::class, $strategy);
        $this->assertEquals('round_robin', $strategy->getEliminationType());
    }

    /**
     * Test factory returns null for unsupported strategy
     */
    public function test_create_unsupported_strategy_returns_null()
    {
        $strategy = $this->factory->createStrategy('invalid_type');
        
        $this->assertNull($strategy);
    }

    /**
     * Test get available strategies
     */
    public function test_get_available_strategies()
    {
        $strategies = $this->factory->getAvailableStrategies();
        
        $this->assertIsArray($strategies);
        $this->assertContains('single_elimination', $strategies);
        $this->assertContains('double_elimination', $strategies);
        $this->assertContains('round_robin', $strategies);
    }

    /**
     * Test is strategy supported
     */
    public function test_is_strategy_supported()
    {
        $this->assertTrue($this->factory->isStrategySupported('single_elimination'));
        $this->assertTrue($this->factory->isStrategySupported('double_elimination'));
        $this->assertTrue($this->factory->isStrategySupported('round_robin'));
        $this->assertFalse($this->factory->isStrategySupported('invalid_type'));
    }

    /**
     * Test register custom strategy
     */
    public function test_register_custom_strategy()
    {
        // Register a strategy
        $result = $this->factory->registerStrategy('mock_strategy', SingleEliminationStrategy::class);
        
        $this->assertTrue($result);
        $this->assertTrue($this->factory->isStrategySupported('mock_strategy'));
    }

    /**
     * Test register custom strategy with non-existent class
     */
    public function test_register_custom_strategy_non_existent_class()
    {
        $result = $this->factory->registerStrategy('bad_strategy', 'NonExistentClass');
        
        $this->assertFalse($result);
    }

    /**
     * Test register custom strategy with invalid interface
     */
    public function test_register_custom_strategy_invalid_interface()
    {
        // Register a class that doesn't implement the interface
        $result = $this->factory->registerStrategy('bad_strategy', \stdClass::class);
        
        $this->assertFalse($result);
    }

    /**
     * Test get strategy info
     */
    public function test_get_strategy_info()
    {
        $info = $this->factory->getStrategyInfo('single_elimination');
        
        $this->assertIsArray($info);
        $this->assertArrayHasKey('type', $info);
        $this->assertArrayHasKey('class', $info);
        $this->assertEquals('single_elimination', $info['type']);
    }

    /**
     * Test get strategy info for unsupported type
     */
    public function test_get_strategy_info_unsupported()
    {
        $info = $this->factory->getStrategyInfo('invalid_type');
        
        $this->assertNull($info);
    }

    /**
     * Test get all strategies info
     */
    public function test_get_all_strategies_info()
    {
        $info = $this->factory->getAllStrategiesInfo();
        
        $this->assertIsArray($info);
        $this->assertCount(3, $info);
        $this->assertArrayHasKey('single_elimination', $info);
        $this->assertArrayHasKey('double_elimination', $info);
        $this->assertArrayHasKey('round_robin', $info);
    }
}
