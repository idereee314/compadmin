<?php
namespace event\matches;

use Illuminate\Support\Facades\Log;

/**
 * Factory for creating and managing tournament elimination strategies
 */
class TournamentEliminationStrategyFactory implements TournamentEliminationFactory {

    /**
     * Registered strategies mapping
     */
    protected $strategies = [];

    /**
     * Constructor - register default strategies
     */
    public function __construct()
    {
        $this->registerDefaultStrategies();
    }

    /**
     * Register default built-in strategies
     */
    protected function registerDefaultStrategies()
    {
        $this->strategies = [
            'single' => SingleEliminationStrategy::class,
            'double_single_bronze' => DoubleEliminationStrategy::class,
            'double' => DoubleEliminationStrategy::class,
            'mjjf' => MJJFEliminationStrategy::class,
            'ijf' => MJJFEliminationStrategy::class,
            // 'round_robin' => RoundRobinStrategy::class,
        ];
    }

    /**
     * Create a tournament elimination strategy instance
     */
    public function createStrategy($type)
    {
        if (!$this->isStrategySupported($type)) {
            Log::warning('Unsupported tournament strategy requested', [
                'type' => $type,
                'available' => array_keys($this->strategies),
            ]);
            return null;
        }

        $className = $this->strategies[$type];
        
        try {
            return new $className($type);
        } catch (\Exception $e) {
            Log::error('Failed to instantiate tournament strategy', [
                'type' => $type,
                'class' => $className,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get list of available strategies
     */
    public function getAvailableStrategies()
    {
        return array_keys($this->strategies);
    }

    /**
     * Check if strategy type is supported
     */
    public function isStrategySupported($type)
    {
        return isset($this->strategies[$type]);
    }

    /**
     * Register a custom strategy
     */
    public function registerStrategy($type, $className)
    {
        // Validate that class implements the interface
        if (!class_exists($className)) {
            Log::error('Custom strategy class does not exist', [
                'type' => $type,
                'class' => $className,
            ]);
            return false;
        }

        // Check if class implements the interface
        $interfaces = class_implements($className);
        if (!isset($interfaces[TournamentEliminationStrategy::class])) {
            Log::error('Custom strategy class does not implement TournamentEliminationStrategy', [
                'type' => $type,
                'class' => $className,
            ]);
            return false;
        }

        $this->strategies[$type] = $className;
        
        Log::info('Custom tournament strategy registered', [
            'type' => $type,
            'class' => $className,
        ]);

        return true;
    }

    /**
     * Get strategy information
     */
    public function getStrategyInfo($type)
    {
        if (!$this->isStrategySupported($type)) {
            return null;
        }

        $strategy = $this->createStrategy($type);
        if (!$strategy) {
            return null;
        }

        return [
            'type' => $strategy->getEliminationType(),
            'class' => $this->strategies[$type],
        ];
    }

    /**
     * Get all registered strategies with details
     */
    public function getAllStrategiesInfo()
    {
        $info = [];
        
        foreach ($this->getAvailableStrategies() as $type) {
            $strategyInfo = $this->getStrategyInfo($type);
            if ($strategyInfo) {
                $info[$type] = $strategyInfo;
            }
        }

        return $info;
    }

    public static function determineRound($totalMatches, $matchOrder, $isDoubleLoser = false)
    {
        // $totalMatches = count of first-round matches (order_no < 100)
        // Bracket size = first-round matches * 2
        $bracketSize = (int) ($totalMatches ?? 0) * 2;

        // Special match types — check first regardless of bracket type
        if ($matchOrder >= 9999) {
            return 'ШИГШЭЭ';
        }
        if ($matchOrder >= 9996 && $matchOrder <= 9998) {
            return 'ХҮРЭЛ МЕДАЛЬ';
        }
        if ($matchOrder > 9990) {
            return 'ХАГАС ШИГШЭЭ (SF)';
        }

        // Best-of-3 format (2 players): 2 preliminary match slots → bracketSize = 4
        // order_no 9999 (Gold/Final) is already caught by the special-match check above.
        if ($bracketSize == 4) {
            if ($matchOrder === 1) return 'ТУЛААН 1';
            if ($matchOrder === 3) return 'ТУЛААН 2';
            return 'ТУЛААН';
        }

        // Round-robin format: 3 players (3 matches → bracketSize 6)
        //                     4 players (6 matches → bracketSize 12, handled below)
        //                     5 players (10 matches → bracketSize 20)
        if ($bracketSize == 6 || $bracketSize == 20) {
            return 'БҮЛГИЙН ТОГЛОЛТ';
        }

        // Pool format: 6 pool matches in round 1 → bracketSize = 12
        // Also covers 4-player round-robin (6 matches, order_no 1-6 < 100 → 'БҮЛГИЙН ТОГЛОЛТ')
        // Round 1 (order_no 1-6): Pool round-robin
        // Round 2 (order_no 201-202): Semi-finals
        if ($bracketSize == 12) {
            if ($matchOrder < 100) {
                return 'БҮЛГИЙН ТОГЛОЛТ';
            }
            $round = (int) ($matchOrder / 100);
            if ($round === 2) {
                return 'ХАГАС ШИГШЭЭ (SF)';
            }
            return 'ШИГШЭЭ';
        }

        if (!in_array($bracketSize, [8, 16, 32], true)) {
            $bracketSize = 8;
        }

        // Round name arrays by bracket size
        if ($bracketSize == 32) {
            $matchesName = ['1/16 ШИГШЭЭ', '1/8 ШИГШЭЭ', 'ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
        } elseif ($bracketSize == 16) {
            $matchesName = ['1/8 ШИГШЭЭ', 'ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
        } else {
            $matchesName = ['ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
        }

        if ($isDoubleLoser) {
            // Losers/repechage bracket: order_no = 2000 + roundNumber*100 + i + 1
            $offset = $matchOrder - 2000;
            $round = ($offset < 100) ? 1 : (int) ($offset / 100);

            Log::debug('Determining round for double elimination', [
                'totalMatches' => $totalMatches,
                'matchOrder' => $matchOrder,
                'offset' => $offset,
                'round' => $round,
                'bracketSize' => $bracketSize,
            ]);
            
            return 'Нөхөн шигшээ';
        }

        // Winners bracket: first round order_no 1-99, round 2+ = roundNumber*100 + i + 1
        $round = ($matchOrder < 100) ? 1 : (int) ($matchOrder / 100);

        return $matchesName[$round - 1] ?? 'ШИГШЭЭ';
    }
}
