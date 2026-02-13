<?php
namespace event\matches;

/**
 * Factory interface for creating tournament elimination strategies
 */
interface TournamentEliminationFactory {
    
    /**
     * Create a tournament elimination strategy instance
     * 
     * @param string $type - Type of elimination (single_elimination, double_elimination, round_robin, swiss)
     * @return TournamentEliminationStrategy|null
     */
    public function createStrategy($type);

    /**
     * Get list of available strategies
     * 
     * @return array
     */
    public function getAvailableStrategies();

    /**
     * Check if strategy type is supported
     * 
     * @param string $type
     * @return bool
     */
    public function isStrategySupported($type);

    /**
     * Register a custom strategy
     * 
     * @param string $type
     * @param string $className - Full class name implementing TournamentEliminationStrategy
     * @return bool
     */
    public function registerStrategy($type, $className);
}
