<?php
namespace event\matches;

use event\EventBrackets;
use event\EventMatches;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Round Robin tournament format
 * Every participant plays against every other participant
 */
class RoundRobinStrategy implements TournamentEliminationStrategy {

    /**
     * Generate bracket structure for round robin
     */
    public function generateBracket($eventId, $participants, $config)
    {
        $validation = $this->validateConfig($config);
        if (!$validation['valid']) {
            Log::error('Invalid configuration for Round Robin', $validation['errors']);
            return [];
        }

        $participantCount = count($participants);
        if ($participantCount < 2) {
            Log::error('Minimum 2 participants required for Round Robin');
            return [];
        }

        $bracketStructure = [];

        foreach ($participants as $index => $participant) {
            $bracketEntry = [
                'event_id' => $eventId,
                'participant_id' => $participant['id'] ?? $participant,
                'position' => $index + 1,
                'total_participants' => $participantCount,
                'status' => 'active',
                'points' => 0,
                'wins' => 0,
                'losses' => 0,
            ];
            $bracketStructure[] = $bracketEntry;
        }

        return $bracketStructure;
    }

    /**
     * Generate all matches for round robin
     */
    public function generateRoundMatches($eventId, $roundNumber, $participantRegistrations = [])
    {
        $matches = [];

        // For round robin, all matches are generated in the first call
        // Subsequent calls return empty since all pairings are already created
        if ($roundNumber !== 1) {
            return [];
        }

        // Use provided registrations, or fetch from database if empty
        if (empty($participantRegistrations)) {
            $participantRegistrations = \DB::table('uq_event_registration')
                ->where('event_id', $eventId)
                ->get(['id'])
                ->pluck('id')
                ->toArray();
        }

        // Generate all possible pairings
        $matchOrder = 1;
        
        for ($i = 0; $i < count($participantRegistrations); $i++) {
            for ($j = $i + 1; $j < count($participantRegistrations); $j++) {
                $matches[] = [
                    'event_id' => $eventId,
                    'reg_one_id' => $participantRegistrations[$i],
                    'reg_two_id' => $participantRegistrations[$j],
                    'order_no' => $matchOrder++,
                    'status' => 'P',
                    'is_double_loser' => 0,
                ];
            }
        }

        return $matches;
    }

    /**
     * Process match result
     */
    public function processMatchResult($matchId, $winnerId, $matchData)
    {
        $match = EventMatches::find($matchId);
        if (!$match) {
            return false;
        }

        $match->reg_win_id = $winnerId;
        $match->status = 'completed';
        $match->end_time = Carbon::now();
        $match->save();

        // Update standings
        $this->updateStandings($match->bracket_id, $winnerId, $match->reg_one_id, $match->reg_two_id);

        Log::info('Round Robin match completed', [
            'match_id' => $matchId,
            'winner_id' => $winnerId,
        ]);

        return true;
    }

    /**
     * Update tournament standings
     */
    private function updateStandings($bracketId, $winnerId, $participantOneId, $participantTwoId)
    {
        $winPoints = $matchData['win_points'] ?? 3;
        $lossPoints = $matchData['loss_points'] ?? 0;

        Log::info('Standings updated', [
            'bracket_id' => $bracketId,
            'winner_id' => $winnerId,
        ]);
    }

    /**
     * Get next round matches (all rounds are generated at once)
     */
    public function getNextRoundMatches($bracketId, $currentRound)
    {
        // All matches are pre-generated during tournament initialization
        return [];
    }

    /**
     * Check if round is complete
     */
    public function isRoundComplete($bracketId, $roundNumber)
    {
        $totalMatches = EventMatches::where('bracket_id', $bracketId)
            ->where('round', $roundNumber)
            ->count();

        $completedMatches = EventMatches::where('bracket_id', $bracketId)
            ->where('round', $roundNumber)
            ->where('status', 'completed')
            ->count();

        return $totalMatches > 0 && $totalMatches === $completedMatches;
    }

    /**
     * Get final standings/ranking
     */
    public function getFinalMatch($bracketId)
    {
        // In round robin, return top ranked match or final fixture
        return EventMatches::where('bracket_id', $bracketId)
            ->orderBy('round', 'desc')
            ->first();
    }

    /**
     * Get tournament standings
     */
    public function getTournamentStandings($bracketId)
    {
        return EventMatches::where('bracket_id', $bracketId)
            ->where('status', 'completed')
            ->selectRaw('reg_win_id as participant_id, COUNT(*) as wins')
            ->groupBy('reg_win_id')
            ->orderBy('wins', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Validate configuration
     */
    public function validateConfig($config)
    {
        $errors = [];

        if (!isset($config['event_id'])) {
            $errors['event_id'] = 'Event ID is required';
        }

        if (isset($config['participant_count']) && $config['participant_count'] > 20) {
            $errors['participant_count'] = 'Round Robin with >20 participants may require many matches';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get elimination type
     */
    public function getEliminationType()
    {
        return 'round_robin';
    }

    /**
     * Calculate total rounds needed (usually 1 for all pairings, or multiple rounds)
     */
    public function calculateTotalRounds($participantCount)
    {
        if ($participantCount <= 1) {
            return 0;
        }
        // In a true round robin, number of rounds = participant count - 1
        // But can be organized as single round with all matches
        return $participantCount - 1;
    }

    /**
     * Compute previous-match references for round robin
     * Round robin doesn't use previous-match progression; all matches are independent
     */
    public function computePreviousReferences($roundMatchesData)
    {
        // Round robin doesn't link matches via previes_mate_id
        // All pairings are determined upfront; no advancement logic
        // Return data unchanged
        return $roundMatchesData;
    }
}
