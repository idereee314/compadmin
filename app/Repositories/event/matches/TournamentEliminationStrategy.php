<?php
namespace event\matches;

/**
 * Interface for tournament elimination strategies
 * Defines contract for different tournament formats
 */
interface TournamentEliminationStrategy {
    
    /**
     * Generate bracket structure for the tournament
     * 
     * @param int $eventId
     * @param array $participants - List of registered participants
     * @param array $config - Tournament configuration
     * @return array - Generated bracket structure
     */
    public function generateBracket($eventId, $participants, $config);

    /**
     * Generate matches for current round
     * 
     * @param int $eventId - Event ID
     * @param int $roundNumber
     * @param array $participantRegistrations - Array of participant registration IDs
     * @return array - Array of matches to be created
     */
    public function generateRoundMatches($eventId, $roundNumber, $participantRegistrations = []);

    /**
     * Process match result and advance winners
     * 
     * @param int $matchId
     * @param int $winnerId
     * @param array $matchData
     * @return bool
     */
    public function processMatchResult($matchId, $winnerId, $matchData);

    /**
     * Get next round matches after current round completion
     * 
     * @param int $bracketId
     * @param int $currentRound
     * @return array
     */
    public function getNextRoundMatches($bracketId, $currentRound);

    /**
     * Check if tournament round is complete
     * 
     * @param int $bracketId
     * @param int $roundNumber
     * @return bool
     */
    public function isRoundComplete($bracketId, $roundNumber);

    /**
     * Get tournament finale/final match
     * 
     * @param int $bracketId
     * @return array|null
     */
    public function getFinalMatch($bracketId);

    /**
     * Get tournament standings/rankings
     * 
     * @param int $bracketId
     * @return array
     */
    public function getTournamentStandings($bracketId);

    /**
     * Validate tournament configuration for this elimination type
     * 
     * @param array $config
     * @return array - ['valid' => bool, 'errors' => array]
     */
    public function validateConfig($config);

    /**
     * Compute previous-match references for linking matches across rounds
     * Strategy-specific implementation for bracket linking logic
     * 
     * @param array $roundMatchesData - Match data organized by round
     * @return array - Updated match data with prev_refs populated
     */
    public function computePreviousReferences($roundMatchesData);

    /**
     * Get elimination type identifier
     * 
     * @return string
     */
    public function getEliminationType();

    /**
     * Calculate total number of rounds needed
     * 
     * @param int $participantCount
     * @return int
     */
    public function calculateTotalRounds($participantCount);
}
