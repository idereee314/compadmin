<?php

namespace Tests\Unit\TournamentMatches;

use PHPUnit\Framework\TestCase;
use event\matches\TournamentEliminationStrategyFactory;
use event\matches\SingleEliminationStrategy;
use event\matches\DoubleEliminationStrategy;

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
        $strategy = $this->factory->createStrategy('single');

        $this->assertInstanceOf(SingleEliminationStrategy::class, $strategy);
        $this->assertEquals('single_elimination', $strategy->getEliminationType());
    }

    /**
     * Test factory creates double elimination strategy
     */
    public function test_create_double_elimination_strategy()
    {
        $strategy = $this->factory->createStrategy('double');

        $this->assertInstanceOf(DoubleEliminationStrategy::class, $strategy);
        $this->assertEquals('double_elimination', $strategy->getEliminationType());
    }

    /**
     * Test factory creates double single bronze strategy
     */
    public function test_create_double_single_bronze_strategy()
    {
       $strategy = $this->factory->createStrategy('double_single_bronze');

        $this->assertInstanceOf(DoubleEliminationStrategy::class, $strategy);
        $this->assertEquals('double_elimination', $strategy->getEliminationType());
    }

    /**
     * Test get available strategies
     */
    public function test_get_available_strategies()
    {
        $strategies = $this->factory->getAvailableStrategies();
        
        $this->assertIsArray($strategies);
        $this->assertContains('single', $strategies);
        $this->assertContains('double', $strategies);
        $this->assertContains('double_single_bronze', $strategies);
        $this->assertContains('mjjf', $strategies);
        $this->assertContains('ijf', $strategies);
    }

    /**
     * Test is strategy supported
     */
    public function test_is_strategy_supported()
    {
        $this->assertTrue($this->factory->isStrategySupported('single'));
        $this->assertTrue($this->factory->isStrategySupported('double'));
        $this->assertTrue($this->factory->isStrategySupported('double_single_bronze'));
        $this->assertTrue($this->factory->isStrategySupported('mjjf'));
        $this->assertTrue($this->factory->isStrategySupported('ijf'));
        $this->assertFalse($this->factory->isStrategySupported('invalid_type'));
    }

    /**
     * Test get strategy info for single elimination
     */
    public function test_get_strategy_info()
    {
        $info = $this->factory->getStrategyInfo('single');

        $this->assertIsArray($info);
        $this->assertArrayHasKey('type', $info);
        $this->assertArrayHasKey('class', $info);
        $this->assertEquals('single_elimination', $info['type']);
    }

    /**
     * Test get all strategies info
     */
    public function test_get_all_strategies_info()
    {
        $info = $this->factory->getAllStrategiesInfo();
        
        $this->assertIsArray($info);
        $this->assertCount(5, $info);
        $this->assertArrayHasKey('single', $info);
        $this->assertArrayHasKey('double', $info);
        $this->assertArrayHasKey('double_single_bronze', $info);
        $this->assertArrayHasKey('mjjf', $info);
        $this->assertArrayHasKey('ijf', $info);
    }
}
