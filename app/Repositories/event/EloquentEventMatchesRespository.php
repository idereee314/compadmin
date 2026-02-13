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

	public function create($input)
	{
		$eventMatches = new EventMatches();
		$eventBracket = findBracket($input['bracket_id']);
		Log::info('Event Bracket: ', (array)$eventBracket);
		if (!$eventBracket) {
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
		if($input['reg_win_id'] != null){
			$eventMatche->reg_win_id = $input['reg_win_id'];
			$eventMatche->status = 'C';
			$eventMatche->end_time = Carbon::now('GMT+8');
			$eventMatche->save();
			$this->updateNextReg($id, $eventMatche);
		}
		$eventBracket = EventBrackets::where('event_id', $eventMatche->event_id)
			->where('entry_id', $eventMatche->entry_id)
			->where('entry_age_id', $eventMatche->entry_age_id)
			->where('entry_belt_id', $eventMatche->entry_belt_id)
			->where('entry_weight_id', $eventMatche->entry_weight_id)
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
			$eventBracket->reg_winner_id = $input['reg_win_id'];
			$eventBracket->save();
		}
		return $eventMatche;

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
		if($input['reg_win_id'] != null){
			$eventMatche->reg_win_id = $input['reg_win_id'];
		}
		if($input['is_double_loser'] ?? false){
			$eventMatche->is_double_loser = true;
			$eventMatche->reg_win_id = null;
		} else{
			$eventMatche->is_double_loser = false;
		}
		$eventMatche->status = 'C';
		if($eventMatche->end_time == null){
			$eventMatche->end_time = Carbon::now('GMT+8');
		}
		$eventMatche->save();
		$this->updateNextReg($id, $eventMatche);
		$eventBracket = EventBrackets::where('event_id', $eventMatche->event_id)
			->where('entry_id', $eventMatche->entry_id)
			->where('entry_age_id', $eventMatche->entry_age_id)
			->where('entry_belt_id', $eventMatche->entry_belt_id)
			->where('entry_weight_id', $eventMatche->entry_weight_id)
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
			$eventBracket->reg_winner_id = $input['reg_win_id'];
			$eventBracket->save();
		}
		return $eventMatche;

	}

	private function updateNextReg($id, $currentMatch)
	{
		$eventMatches = EventMatches::where('previes_mate_id1', $id)
			->orWhere('previes_mate_id2', $id)->get();
		$winner = $currentMatch->reg_win_id;
		$losser = $currentMatch->reg_one_id == $winner ? $currentMatch->reg_two_id : $currentMatch->reg_one_id;
		if($eventMatches->count() > 0) {
			foreach ($eventMatches as $eventMatch) {
				if($eventMatch ->order_no == 9998 || $eventMatch -> is_double_loser){
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
			}
		}
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
			'bracket.entry:id,name',
			'bracket.entry:id,name',
			'bracket.age:id,start_age,end_age',
			'bracket.age:id,start_age,end_age',
			'bracket.belt:id,name',
			'bracket.weight:id,weight',
		])->select('*')->find($id);

		if (!$match) {
			return collect(); // or throw exception / return empty if not found
		}
		$count = EventMatches::where('event_id', $match->event_id)->where('bracket_id', $match->bracket_id)
			->where('order_no', 100, '<')
			->count();

		$match->bracket->round = TournamentEliminationStrategyFactory::determineRound(
			$count,
			$match->order_no,
			$match->is_double_loser
		);
		$match->bracket->is_double_loser = $match->is_double_loser;

		Log::info('round determined', [
			'round' => $match->bracket->round,
		]);

		// Return combined data
		return [
			'registrations' => collect([
				$match->regOne ?? null,
				$match->regTwo ?? null,
				$match->regWin ?? null
			])->filter(),
			'bracket' => $match->bracket
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
		
		$participants = EventRegistration::where('event_id', $event_id)
			->where('entry_id', $input['entry_id'])
			->where('entry_age_id', $input['entry_age_id'])
			->where('entry_belt_id', $input['entry_belt_id'])
			->where('entry_weight_id', $input['entry_weight_id'])
			->where('status', 'approved')->get()->pluck('id')->toArray();

		if (empty($participants)) {
			return collect();
		}

		$event = EventConfig::where('event_id', $event_id)
			->first();
		$bracketType = EventBracketType::find($event->bracket_type_id);

		$tournamentService = app()->make(TournamentMatchServiceAlias::class);
		return $tournamentService->initializeTournament($event_id, $bracketType->code, $participants, $mateBracket);
	}

}
