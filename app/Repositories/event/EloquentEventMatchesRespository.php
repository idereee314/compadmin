<?php namespace event;

use event\EventConfig;
use event\EventMatches;
use event\EventMateBracket;
use core\sessions\Sessions;
use event\matches\TournamentEliminationStrategyFactory;
use event\matches\TournamentMatchService as TournamentMatchServiceAlias;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use SecurityHelper;
use Carbon\Carbon;
use Session;
use Config;

class EloquentEventMatchesRespository implements EventMatchesRespository {

	public function find($id)
	{
		return EventMatches::find($id);
	}

	public function findBracket($id)
	{
		return EventBrackets::find($id);
	}

	public function findMateBracket($id)
	{
		return EventMateBracket::find($id);
	}

	public function create($input)
	{
		$eventMatches = new EventMatches();
		$eventBracket = $this->findBracket($input['bracket_id']);
		if ($eventBracket) {
			$eventMatches->event_id = $eventBracket['event_id'];
			$eventMatches->bracket_id = $eventBracket['bracket_id'];
			$eventMatches->reg_one_id = $eventBracket['reg_one_id'];
			$eventMatches->reg_two_id = $eventBracket['reg_two_id'];
		} else{
			$eventMatches->event_id = $input['event_id'];
			$eventMatches->bracket_id = $input['bracket_id'];
			$eventMatches->reg_one_id = null;
			$eventMatches->reg_two_id = null;
			$eventMatches->previes_mate_id1 = $input['previes_mate_id1'];
			$eventMatches->previes_mate_id2 = $input['previes_mate_id2'];
		}
		$eventMatches->save();

		return $eventMatches;
	}

 	public function update($id, $input)
	{
		$eventMatches = $this->find($id);
		$eventMatches->previes_mate_id1 = $input['previes_mate_id1'];
		$eventMatches->previes_mate_id2 = $input['previes_mate_id2'];
		if($input['end_time'] != null){
			$eventMatches->end_time = $input['end_time'];
		}
		if($input['reg_one_id'] != null){
			$eventMatches->reg_one_id = $input['reg_one_id'];
		}
		if($input['reg_two_id'] != null){
			$eventMatches->reg_two_id = $input['reg_two_id'];
		}
		if($input['status'] != null){
			$eventMatches->status = $input['status'];
		}
		$eventMatches->save();
		return $eventMatches;
	}

	public function delete($id)
	{
		$eventMatches = $this->find($id);
		$eventMatches->delete();
	}

	public function updateWinner($id, $input)
	{
		$eventMatche = $this->find($id);
		$bracketMat = $this->findMateBracket($eventMatche->bracket_id);
		
		$winMethod = $input['win_method'] ?? null;
		$isDraw = in_array($winMethod, ['DRAW', 'DOUBLE WO/DQ', 'DOUBLE NO SHOW']);

		// Save scores, advantages, penalties
		$eventMatche->red_score     = (int) ($input['red_score'] ?? 0);
		$eventMatche->blue_score    = (int) ($input['blue_score'] ?? 0);
		$eventMatche->red_advantage = (int) ($input['red_advantage'] ?? 0);
		$eventMatche->blue_advantage= (int) ($input['blue_advantage'] ?? 0);
		$eventMatche->red_penalty   = (int) ($input['red_penalty'] ?? 0);
		$eventMatche->blue_penalty  = (int) ($input['blue_penalty'] ?? 0);
		$eventMatche->win_method    = $winMethod;

		// Calculate match points
		$matchPoints = $this->calculateMatchPoints($winMethod, $eventMatche->reg_one_id, $input['reg_win_id'] ?? null);
		$eventMatche->red_match_points  = $matchPoints['red'];
		$eventMatche->blue_match_points = $matchPoints['blue'];

		$byeMatchIds = [];
		if($isDraw){
			$eventMatche->reg_win_id = null;
			$eventMatche->status = 'C';
			$eventMatche->end_time = Carbon::now('GMT+8');
			$eventMatche->save();
			// No winner advances — check if opponents in next matches get BYE
			$byeMatchIds = $this->handleDrawBye($id, $eventMatche, $bracketMat);
		} elseif(!empty($input['reg_win_id'])){
			$eventMatche->reg_win_id = $input['reg_win_id'];
			$eventMatche->status = 'C';
			$eventMatche->end_time = Carbon::now('GMT+8');
			$eventMatche->save();
			$this->updateNextReg($id, $eventMatche, $bracketMat);
			$this->cancelBestOf3DeciderIfUnnecessary($eventMatche);
		} else {
			$eventMatche->save();
		}

		$eventBracket = EventBrackets::where('event_id', $bracketMat->event_id)
			->where('entry_id', $bracketMat->entry_id)
			->where('entry_age_id', $bracketMat->entry_age_id)
			->where('entry_belt_id', $bracketMat->entry_belt_id)
			->where('entry_weight_id', $bracketMat->entry_weight_id)
			->where(function($query) use ($eventMatche) {
				$query->where(function($q) use ($eventMatche) {
					$q->where('reg_one_id', $eventMatche->reg_one_id)
					  ->where('reg_two_id', $eventMatche->reg_two_id);
				})->orWhere(function($q) use ($eventMatche) {
					$q->where('reg_one_id', $eventMatche->reg_two_id)
					  ->where('reg_two_id', $eventMatche->reg_one_id);
				});
			})
			->first();
		if($eventBracket){
			$eventBracket->reg_winner_id = $input['reg_win_id'] ?? null;
			$eventBracket->save();
		}

		$eventMatche->bye_match_ids = $byeMatchIds;
		return $eventMatche;

	}

	/**
	 * Calculate match points for both players based on win method.
	 * SUBMISSION win: winner +50, loser 0
	 * DISQUALIFICATION win: winner 0, loser (DQ'd) -50
	 * DRAW / DOUBLE NO SHOW: both 0
	 * DOUBLE WO/DQ: both -50
	 * Other wins (POINTS, DECISION, WALKOVER, NO SHOW): both 0
	 */
	private function calculateMatchPoints($winMethod, $regOneId, $regWinId)
	{
		$redPoints = 0;
		$bluePoints = 0;

		if ($winMethod === 'DOUBLE WO/DQ') {
			$redPoints = -50;
			$bluePoints = -50;
		} elseif (in_array($winMethod, ['DRAW', 'DOUBLE NO SHOW'])) {
			$redPoints = 0;
			$bluePoints = 0;
		} elseif ($winMethod === 'SUBMISSION' && $regWinId) {
			// Winner gets +50
			if ($regWinId == $regOneId) {
				$redPoints = 50;
			} else {
				$bluePoints = 50;
			}
		} elseif ($winMethod === 'DISQUALIFICATION' && $regWinId) {
			// Loser (the DQ'd player) gets -50
			if ($regWinId == $regOneId) {
				// Red won, blue was DQ'd
				$bluePoints = -50;
			} else {
				// Blue won, red was DQ'd
				$redPoints = -50;
			}
		}

		return ['red' => $redPoints, 'blue' => $bluePoints];
	}

	// public function setDoubleLoser($id, $input)
	// {
	// 	$eventMatche = $this->find($id);
	// 	$eventMatche->status = 'C';
	// 	$eventMatche->is_double_loser = true;
	// 	$eventMatche->end_time = Carbon::now('GMT+8');
	// 	$eventMatche->save();
	// 	$this->updateNextReg($id, $eventMatche);
	// 	return $eventMatche;

	// }
	
	public function editWinner($id, $input)
	{
		$eventMatche = $this->find($id);
		$bracketMat = $this->findMateBracket($eventMatche->bracket_id);

		// Save scores if provided
		if(isset($input['red_score'])){
			$eventMatche->red_score     = (int) ($input['red_score'] ?? 0);
			$eventMatche->blue_score    = (int) ($input['blue_score'] ?? 0);
			$eventMatche->red_advantage = (int) ($input['red_advantage'] ?? 0);
			$eventMatche->blue_advantage= (int) ($input['blue_advantage'] ?? 0);
			$eventMatche->red_penalty   = (int) ($input['red_penalty'] ?? 0);
			$eventMatche->blue_penalty  = (int) ($input['blue_penalty'] ?? 0);
			$eventMatche->win_method    = $input['win_method'] ?? null;

			$matchPoints = $this->calculateMatchPoints($eventMatche->win_method, $eventMatche->reg_one_id, $input['reg_win_id'] ?? null);
			$eventMatche->red_match_points  = $matchPoints['red'];
			$eventMatche->blue_match_points = $matchPoints['blue'];
		}

		if($input['reg_win_id'] != null){
			$eventMatche->reg_win_id = $input['reg_win_id'];
		}
		if($input['is_double_loser'] ?? false){
			$eventMatche->is_double_loser = true;
			$eventMatche->reg_win_id = null;
		}
		$eventMatche->status = 'C';
		if($eventMatche->end_time == null){
			$eventMatche->end_time = Carbon::now('GMT+8');
		}
		$eventMatche->save();
		$this->updateNextReg($id, $eventMatche, $bracketMat);
		$this->cancelBestOf3DeciderIfUnnecessary($eventMatche);
		$eventBracket = EventBrackets::where('event_id', $eventMatche->event_id)
			->where('entry_id', $bracketMat->entry_id)
			->where('entry_age_id', $bracketMat->entry_age_id)
			->where('entry_belt_id', $bracketMat->entry_belt_id)
			->where('entry_weight_id', $bracketMat->entry_weight_id)
			->where(function($query) use ($eventMatche) {
				$query->where(function($q) use ($eventMatche) {
					$q->where('reg_one_id', $eventMatche->reg_one_id)
					  ->where('reg_two_id', $eventMatche->reg_two_id);
				})->orWhere(function($q) use ($eventMatche) {
					$q->where('reg_one_id', $eventMatche->reg_two_id)
					  ->where('reg_two_id', $eventMatche->reg_one_id);
				});
			})
			->first();
		if($eventBracket){
			$eventBracket->reg_winner_id = $input['reg_win_id'] ?? null;
			$eventBracket->save();
		}
		return $eventMatche;

	}

	/**
	 * Best-of-3 clean-up: when Match 2 (order_no=3) completes and one player
	 * has already won Match 1 (order_no=1) as well, the decider match
	 * (order_no=9999) is no longer needed and is deleted from the bracket.
	 *
	 * Detection: a best-of-3 bracket has exactly 2 preliminary matches
	 * (order_no < 100), with order_nos 1 and 3. This distinguishes it from
	 * pool format (6 preliminary matches) and round-robin formats.
	 */
	private function cancelBestOf3DeciderIfUnnecessary($match)
	{
		// Only act when Match 2 (order_no=3) finishes with a real winner
		if ((int) $match->order_no !== 3 || !$match->reg_win_id) {
			return;
		}

		// Confirm this is a best-of-3 bracket (exactly 2 preliminary matches)
		$prelimCount = EventMatches::where('bracket_id', $match->bracket_id)
			->where('order_no', '<', 100)
			->count();
		if ($prelimCount !== 2) {
			return;
		}

		// Match 1 must also be complete
		$match1 = EventMatches::where('bracket_id', $match->bracket_id)
			->where('order_no', 1)
			->first();
		if (!$match1 || $match1->status !== 'C' || !$match1->reg_win_id) {
			return;
		}

		// Same player won both Match 1 and Match 2 → 2-0, decider unnecessary
		if ((int) $match1->reg_win_id === (int) $match->reg_win_id) {
			EventMatches::where('bracket_id', $match->bracket_id)
				->where('order_no', 9999)
				->delete();
		}
	}

	private function updateNextReg($id, $currentMatch, $braketData)
	{
		$eventMatches = EventMatches::where('previes_mate_id1', $id)
			->orWhere('previes_mate_id2', $id)->get();
		$winner = $currentMatch->reg_win_id;
		$losser = $currentMatch->reg_one_id == $winner ? $currentMatch->reg_two_id : $currentMatch->reg_one_id;
		if($eventMatches->count() > 0) {
			foreach ($eventMatches as $eventMatch) {
				// Send the LOSER only when the destination is a losers bracket match
				// AND the source is a winners bracket match (loser drops down).
				// When the source is itself a losers bracket match, the WINNER advances
				// and the loser is eliminated.
				$shouldSendLoser = ($eventMatch->order_no == 9998 || $eventMatch->is_double_loser)
					&& !$currentMatch->is_double_loser;

				if($shouldSendLoser){
					if ($eventMatch->previes_mate_id1 == $id) {
						$eventMatch->reg_one_id = $losser;
					} else {
						$eventMatch->reg_two_id = $losser;
					}
				} else{
					if ($eventMatch->previes_mate_id1 == $id) {
						$eventMatch->reg_one_id = $winner;
					} else {
						$eventMatch->reg_two_id = $winner;
					}
				}
				$eventMatch->save();

				// After filling one slot, check if the other slot is a BYE
				$this->checkAndHandleBye($eventMatch, $braketData);
			}
		}
	}

	/**
	 * Handle BYE advancement after a draw result.
	 * When no winner advances, the opponent in the next match gets a BYE (auto-win).
	 * Returns array of auto-completed BYE match IDs for NEXT button skipping.
	 */
	private function handleDrawBye($matchId, $currentMatch, $braketData)
	{
		$byeMatchIds = [];
		$nextMatches = EventMatches::where('previes_mate_id1', $matchId)
			->orWhere('previes_mate_id2', $matchId)->get();

		foreach ($nextMatches as $nextMatch) {
			// The slot fed by this draw match stays null (no one advances).
			// Check if the other slot already has a player → BYE win.
			$byeIds = $this->checkAndHandleBye($nextMatch, $braketData);
			$byeMatchIds = array_merge($byeMatchIds, $byeIds);
		}

		return $byeMatchIds;
	}

	/**
	 * Check if a match has a BYE condition and auto-complete it.
	 * BYE = one slot has a real player, the other slot's feeder match is Complete with no winner.
	 * Returns array of auto-completed match IDs (including cascaded BYEs).
	 */
	private function checkAndHandleBye($match, $braketData)
	{
		$byeMatchIds = [];

		// Skip if match is already completed
		if ($match->status === 'C') {
			return $byeMatchIds;
		}

		$slotOneBye = $this->isSlotBye($match->reg_one_id, $match->previes_mate_id1);
		$slotTwoBye = $this->isSlotBye($match->reg_two_id, $match->previes_mate_id2);

		if ($slotOneBye && $slotTwoBye) {
			// Both slots are BYE — complete as double BYE (no winner), cascade further
			$match->status = 'C';
			$match->win_method = 'DOUBLE BYE';
			$match->end_time = Carbon::now('GMT+8');
			$match->save();
			$byeMatchIds[] = $match->id;

			// Cascade: this match also produces no winner, so check next matches
			$cascaded = $this->handleDrawBye($match->id, $match, $braketData);
			$byeMatchIds = array_merge($byeMatchIds, $cascaded);

		} elseif ($slotOneBye && $match->reg_two_id) {
			// Slot one is BYE, slot two has a real player → player two wins by BYE
			$match->reg_win_id = $match->reg_two_id;
			$match->status = 'C';
			$match->win_method = 'BYE';
			$match->end_time = Carbon::now('GMT+8');
			$match->save();
			$byeMatchIds[] = $match->id;

			// Advance the BYE winner to next matches
			$this->updateNextReg($match->id, $match, $braketData);

		} elseif ($slotTwoBye && $match->reg_one_id) {
			// Slot two is BYE, slot one has a real player → player one wins by BYE
			$match->reg_win_id = $match->reg_one_id;
			$match->status = 'C';
			$match->win_method = 'BYE';
			$match->end_time = Carbon::now('GMT+8');
			$match->save();
			$byeMatchIds[] = $match->id;

			// Advance the BYE winner to next matches
			$this->updateNextReg($match->id, $match, $braketData);
		}

		return $byeMatchIds;
	}

	/**
	 * Check if a match slot is a BYE (no player, feeder match complete with no winner).
	 */
	private function isSlotBye($regId, $feederMatchId)
	{
		// If the slot already has a player, it's not a BYE
		if ($regId) {
			return false;
		}
		// If there's no feeder match (first round), it's not a BYE
		if (!$feederMatchId) {
			return false;
		}
		// Check if the feeder match is complete with no winner
		$feederMatch = EventMatches::find($feederMatchId);
		if ($feederMatch && $feederMatch->status === 'C' && !$feederMatch->reg_win_id) {
			return true;
		}
		return false;
	}

	public function getMatchesByEventId($id)
	{
		$match = EventMatches::with([
			'regOne:id,member_id,academy_id',
			'regTwo:id,member_id,academy_id',
			'regOne.member:id,firstname,lastname',
			'regTwo.member:id,firstname,lastname',
			'regOne.academy:id,name',
			'regTwo.academy:id,name',
			'regWin:id,member_id,academy_id',
			'bracket',
			'bracket.entry:id,name,duration,gender_code',
			'bracket.age:id,start_age,end_age',
			'bracket.age:id,start_age,end_age',
			'bracket.belt:id,name',
			'bracket.weight:id,weight',
		])->select('*')->find($id);

		if (!$match) {
			return collect(); // or throw exception / return empty if not found
		}
		$count = EventMatches::where('event_id', $match->event_id)->where('bracket_id', $match->bracket_id)
			->where('order_no', '<', 100)
			->count();
		$totalBracketMatches = EventMatches::where('event_id', $match->event_id)
			->where('bracket_id', $match->bracket_id)
			->count();

		$token = DB::select('SELECT A.* FROM uq_country_abbrevation A inner join uq_country uc ON a.abbrevation = uc.abbreviation LEFT JOIN uq_member  B on uc.id = b.country_id  WHERE B.id = ?', [
			$match->regOne->member_id ?? 0,
		]);

		$regOne = $match->regOne;
		if ($regOne) {
			$regOne->abb_full = $token[0]->token ?? null;
			$regOne->abb = $token[0]->abbrevation ?? null;
		}

		$token = DB::select('SELECT A.* FROM uq_country_abbrevation A inner join uq_country uc ON a.abbrevation = uc.abbreviation LEFT JOIN uq_member  B on uc.id = b.country_id  WHERE B.id = ?', [
			$match->regTwo->member_id ?? 0,
		]);

		$regTwo = $match->regTwo;
		if ($regTwo) {
			$regTwo->abb_full = $token[0]->token ?? null;
			$regTwo->abb = $token[0]->abbrevation ?? null;
		}

		// Check BYE status for each slot
		$regOneBye = false;
		$regTwoBye = false;
		if (!$regOne && $match->previes_mate_id1) {
			$feeder = EventMatches::find($match->previes_mate_id1);
			$regOneBye = $feeder && $feeder->status === 'C' && !$feeder->reg_win_id;
		}
		if (!$regTwo && $match->previes_mate_id2) {
			$feeder = EventMatches::find($match->previes_mate_id2);
			$regTwoBye = $feeder && $feeder->status === 'C' && !$feeder->reg_win_id;
		}

		$bracket = $match->bracket;
		if ($bracket) {
			$bracket->round = TournamentEliminationStrategyFactory::determineRound(
				$count,
				$match->order_no,
				$match->is_double_loser,
				$totalBracketMatches
			);
			$bracket->is_double_loser = $match->is_double_loser;
		}

		// Return combined data — preserve null positions for BYE display
		return [
			'registrations' => [
				$regOne,   // index 0: red player (null if BYE)
				$regTwo,   // index 1: blue player (null if BYE)
				$match->regWin ?? null,  // index 2: winner
			],
			'bracket' => $bracket,
			'is_bye' => [$regOneBye, $regTwoBye],
		];
	}

	public function generateMatches($event_id, $input, $day, $mat)
	{
		$mateBracket = EventMateBracket::where('event_id', $event_id)
			->where('entry_id', $input['entry_id'])
			->where('entry_age_id', $input['entry_age_id'])
			->where('entry_belt_id', $input['entry_belt_id'])
			->where('entry_weight_id', $input['entry_weight_id'])
			->where('day_id', $day)
			->where('mat_id', $mat)
			->first();
		
		if (!$mateBracket) {
			Log::warning('generateMatches: mateBracket not found', compact('event_id', 'day', 'mat', 'input'));
			return collect();
		}

		// Delete any existing matches for this bracket to avoid duplicates
		EventMatches::where('bracket_id', $mateBracket->id)->delete();

		$participants = EventBrackets::where('event_id', $event_id)
			->where('entry_id', $input['entry_id'])
			->where('entry_age_id', $input['entry_age_id'])
			->where('entry_belt_id', $input['entry_belt_id'])
			->where('entry_weight_id', $input['entry_weight_id'])
			->get()->flatMap(function ($row) {
				return [$row->reg_one_id, $row->reg_two_id];
			})
			->filter()
			->values()
			->toArray();
		Log::info('Participants for match generation: ', [
			'participants' => $participants,
		]);

		if (empty($participants)) {
			return collect();
		}

		$event = EventConfig::where('event_id', $event_id)
			->first();

		if (!$event || !$event->event_bracket_type_id) {
			Log::warning('generateMatches: no bracket type configured for event', compact('event_id'));
			return collect();
		}

		$bracketType = EventBracketType::find($event->event_bracket_type_id);

		if (!$bracketType) {
			Log::warning('generateMatches: bracket type not found', ['id' => $event->event_bracket_type_id]);
			return collect();
		}

		Log::info('Generating matches for Event ID: '.$event_id, [
			'bracketType' => $bracketType->code,
			'mateBracket' => $mateBracket->toArray(),
		]);

		$tournamentService = app()->make(TournamentMatchServiceAlias::class);
		return $tournamentService->initializeTournament($event_id, $bracketType->code, $participants, $mateBracket);
	}

	public function getPrevMatches($match_id, $request)
	{
	    $currentMatch = $this->find($match_id);
	    if (!$currentMatch) return null;
	
	    $prev = EventMatches::where('event_id', $currentMatch->event_id)
	        ->where('bracket_id', $currentMatch->bracket_id)
	        ->where('display_order', '<', $currentMatch->display_order)
	        ->where('display_order', '>', 0)
	        ->orderBy('display_order', 'desc')
	        ->first();
	
	    return $prev ? $prev->id : null;
	}
	
	public function getNextMatches($match_id, $request)
	{
	    $currentMatch = $this->find($match_id);
	    if (!$currentMatch) return null;
	
	    $next = EventMatches::where('event_id', $currentMatch->event_id)
	        ->where('bracket_id', $currentMatch->bracket_id)
	        ->where('display_order', '>', $currentMatch->display_order)
	        ->orderBy('display_order', 'asc')
	        ->first();
	
	    return $next ? $next->id : null;
	}
}
