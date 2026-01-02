<?php namespace event;

use event\EventConfig;
use event\EventMatches;
use event\EventMateBracket;
use core\sessions\Sessions;

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
	

	public function editWinner($id, $input)
	{
		$eventMatche = $this->find($id);
		if($input['reg_win_id'] != null){
			$eventMatche->reg_win_id = $input['reg_win_id'];
			$eventMatche->status = 'C';
			if(isset($eventMatche->end_time) == null){
				$eventMatche->end_time = Carbon::now('GMT+8');
			}
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

	private function updateNextReg($id, $previesMatch)
	{
		$eventMatches = EventMatches::where('previes_mate_id1', $id)
			->orWhere('previes_mate_id2', $id)->get();
		$winner = $previesMatch->reg_win_id;
		$losser = $previesMatch->reg_one_id == $winner ? $previesMatch->reg_two_id : $previesMatch->reg_one_id;
		if($eventMatches->count() > 0) {
			foreach ($eventMatches as $eventMatch) {
				if($eventMatch ->order_no == 9998){
					if ($eventMatch->reg_one_id == null) {
						$eventMatch->reg_one_id = $losser;
					} elseif ($eventMatch->reg_two_id == null) {
						$eventMatch->reg_two_id = $losser;
					}
				} else{
					if ($eventMatch->reg_one_id == null) {
						$eventMatch->reg_one_id = $winner;
					} elseif ($eventMatch->reg_two_id == null) {
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
			'bracket'
		])->find($id);

		if (!$match) {
			return collect(); // or throw exception / return empty if not found
		}
		Log::info('Match Details: '. $match->bracket);

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
		Log::info('Mate Bracket: ' . $mateBracket);
		Log::info('Mate Bracket ID: ' . $mateBracket->id);
		$eventMatches = $this->generateInitMatchs($event_id, $mateBracket);

		$allMatches = $eventMatches; // Store all matches (current and future)
		while (count($eventMatches) > 2) {
			$futureMatches = $this->generateGeneralMatch($event_id, $eventMatches, 0, $mateBracket);
			$eventMatches = $futureMatches; // Set future matches as the current matches for the next round
		}
		
		$this->generateQuarterFinals($event_id, $eventMatches, $mateBracket);
		
		return [
			'all_matches' => $allMatches,
			'final_match' => $eventMatches[0] ?? null, // The last match
		];
	}

	private function generateQuarterFinals($event_id, $eventMatches, $mateBracket){
		$eventBrackets = DB::table('uq_event_brackets')
			->where('event_id', $event_id)
			->where('entry_id', $mateBracket->entry_id)
			->where('entry_age_id', $mateBracket->entry_age_id)
			->where('entry_belt_id', $mateBracket->entry_belt_id)
			->where('entry_weight_id', $mateBracket->entry_weight_id)
			->first();
		if (!$eventBrackets) {
			Log::info('No brackets found for quarter finals generation for event_id: ' . $event_id);
			throw new \Exception('No brackets found for the provided event and entry details.');
		}
		if(count($eventMatches) == 2){
			$futureMatch = new EventMatches();
			$futureMatch->event_id = $event_id;
			$futureMatch->previes_mate_id1 = $eventMatches[0]->id;
			$futureMatch->previes_mate_id2 = $eventMatches[1]->id;
			$futureMatch->status = 'P'; // Pending status
			$futureMatch->order_no = 9998; // Set to a bronze medal match
			$futureMatch->bracket_id = $mateBracket->id;
			Log::info('Bracker final id: ' . $mateBracket->id);
			$futureMatch->save();
		}

		Log::info('Count of event matches: ' . count($eventMatches));
		Log::info('eventMatches: ', $eventMatches);
		$this->generateGeneralMatch($event_id, $eventMatches, 9999, $mateBracket);
	}

	private function generateInitMatchs($event_id, $mateBracket){
		Log::info('Generating initial matches for event_id: ' . $event_id);
		// Step 1: Retrieve the event brackets
		$eventBrackets = DB::table('uq_event_brackets')
			->where('event_id', $event_id)
			->where('entry_id', $mateBracket->entry_id)
			->where('entry_age_id', $mateBracket->entry_age_id)
			->where('entry_belt_id', $mateBracket->entry_belt_id)
			->where('entry_weight_id', $mateBracket->entry_weight_id)
			->get();
		$eventMatches = [];

		// Step 2: Save the brackets as initial matches
		foreach ($eventBrackets as $bracket) {
			// Check if the match already exists
			$existingMatch = EventMatches::where('event_id', $bracket->event_id)
			    ->where('bracket_id', $mateBracket->id)
				->where('reg_one_id', $bracket->reg_one_id)
				->where('reg_two_id', $bracket->reg_two_id)
				->first();
				
			if ($existingMatch) {
				$eventMatches[] = $existingMatch; // Add the existing match to the list
				continue; // Skip creating a duplicate match
			}

			// Create a new match if it doesn't exist
			$match = new EventMatches();
			$match->event_id = $bracket->event_id;
			$match->reg_one_id = $bracket->reg_one_id;
			$match->reg_two_id = $bracket->reg_two_id;
			$match->order_no = 0;
			$match->status = 'P'; // Pending status
			$match->bracket_id = $mateBracket->id;
			Log::info('Bracker id init: ' . $mateBracket->id);
			if($bracket->reg_one_id == null || $bracket->reg_two_id == null){
				$match->reg_win_id = $bracket->reg_one_id ?? $bracket->reg_two_id;
				$match->status = 'C'; // Complete status
			}
			$match->save();
			$eventMatches[] = $match;
		}
		return $eventMatches;
	}

	private function generateGeneralMatch($event_id, $eventMatches, $round_counter, $mateBracket)
	{
		$futureMatches = [];
		$matchCount = count($eventMatches);

		// Set bracket_id if EventMateBracket exists
		if ($mateBracket === null) {
			Log::info('No mate bracket found for event_id: ' . $event_id);
			throw new \Exception('No mate bracket found for the provided event and entry details.');
		}
		
		for ($i = 0; $i < $matchCount; $i += 2) {
			if (isset($eventMatches[$i + 1])) {
				// Check if the future match already exists
				$existingFutureMatch = EventMatches::where('event_id', $event_id)
					->where('bracket_id', $mateBracket->id)
					->where('previes_mate_id1', $eventMatches[$i]->id)
					->where('previes_mate_id2', $eventMatches[$i + 1]->id)
					->where('order_no',  $round_counter)
					->first();

				if ($existingFutureMatch) {
					Log::info('Existing future match found: ', ['match_id' => $existingFutureMatch->id,  $round_counter]);
					$futureMatches[] = $existingFutureMatch; // Add the existing match to the list
					$allMatches[] = $existingFutureMatch;
					continue; // Skip creating a duplicate match
				}

				// Create a new future match if it doesn't exist
				$futureMatch = new EventMatches();
				$futureMatch->event_id = $event_id;
				$futureMatch->previes_mate_id1 = $eventMatches[$i]->id;
				$futureMatch->previes_mate_id2 = $eventMatches[$i + 1]->id;
				$futureMatch->status = 'P'; // Pending status
				$futureMatch->order_no = $round_counter;
				$futureMatch->bracket_id = $mateBracket->id;
				Log::info('Bracker following id: ' . $mateBracket->id);
				if($eventMatches[$i]->reg_win_id != null){
					if($futureMatch->reg_one_id == null){
						$futureMatch->reg_one_id = $eventMatches[$i]->reg_win_id;
					} else{
						$futureMatch->reg_two_id = $eventMatches[$i]->reg_win_id;
					}
				}
				if($eventMatches[$i + 1]->reg_win_id != null){
					if($futureMatch->reg_one_id == null){
						$futureMatch->reg_one_id = $eventMatches[$i + 1]->reg_win_id;
					} else{
						$futureMatch->reg_two_id = $eventMatches[$i + 1]->reg_win_id;
					}
				}
				$futureMatch->save();

				$futureMatches[] = $futureMatch;
			}
		}
		return $futureMatches;
	}
}
