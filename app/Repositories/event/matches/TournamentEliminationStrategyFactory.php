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
            return new $className();
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

    public static function determineRound( $totalMatches, $matchOrder, $isDoubleLoser = false)
    {
        $round = 1;
        $matchesInRound = 1;
        $bracketSize = (int) ($totalMatches ?? 0);

        if (!in_array($bracketSize, [8,16,32], true)) {
            $membersCount = is_countable($members ?? null) ? count($members) : 0;
            $bracketSize  = ($membersCount >= 16) ? 32 : (($membersCount >= 8) ? 16 : 8);
        }

        if ($isDoubleLoser) {
            // Double elimination logic can be more complex; this is a simplified version
            $matchesInRound = $matchOrder - 2000;
            $round = (int) ($matchesInRound / 100) + 1;
            Log::debug('Determining round for double elimination', [
                'totalMatches' => $totalMatches,
                'matchOrder' => $matchOrder,
                'matchesInRound' => $matchesInRound,
                'round' => $round,
                'bracketSize' => $bracketSize,
            ]);
            if($matchOrder >= 9999){
                return 'ШИГШЭЭ';
            } elseif($matchOrder > 9990){
                return 'ХАГАС ШИГШЭЭ (SF)';
            } elseif($bracketSize== 32){
                $matchesName = ['1/16 ШИГШЭЭ','1/8 ШИГШЭЭ','ШӨВГИЙН 8 (QF)','ХАГАС ШИГШЭЭ (SF)','ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            } elseif($bracketSize== 16){
                $matchesName = ['1/8 ШИГШЭЭ', 'ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            } elseif($bracketSize== 8){
                $matchesName = ['ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            }
        } else {
            $round = (int) ($matchesInRound / 100) + 1;
            if($matchOrder >= 9999){
                return 'ШИГШЭЭ';
            } elseif($matchOrder > 9990){
                return 'ХАГАС ШИГШЭЭ (SF)';
            } elseif($bracketSize== 32){
                $matchesName = ['1/16 ШИГШЭЭ','1/8 ШИГШЭЭ','ШӨВГИЙН 8 (QF)','ХАГАС ШИГШЭЭ (SF)','ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            } elseif($bracketSize== 16){
                $matchesName = ['1/8 ШИГШЭЭ', 'ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            } elseif($bracketSize== 8){
                $matchesName = ['ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'];
                return $matchesName[$round-1] ?? 'ШИГШЭЭ';
            }
        }

        return $round;
    }
}
