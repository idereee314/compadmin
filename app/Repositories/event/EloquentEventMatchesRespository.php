<?php namespace event;

use event\EventConfig;
use event\EventMatches;
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
use Carbon;
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
		if (!$eventBracket) {
			$eventMatches->event_id = $eventBracket['event_id'];
			$eventMatches->entry_id = $eventBracket['entry_id'];
			$eventMatches->entry_age_id = $eventBracket['entry_age_id'];
			$eventMatches->entry_belt_id = $eventBracket['entry_belt_id'];
			$eventMatches->entry_weight_id = $eventBracket['entry_weight_id'];
			$eventMatches->reg_one_id = $eventBracket['reg_one_id'];
			$eventMatches->reg_two_id = $eventBracket['reg_two_id'];
		} else{
			$eventMatches->event_id = $input['event_id'];
			$eventMatches->entry_id = $input['entry_id'];
			$eventMatches->entry_age_id = $input['entry_age_id'];
			$eventMatches->entry_belt_id = $input['entry_belt_id'];
			$eventMatches->entry_weight_id = $input['entry_weight_id'];
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
		$eventMatches = $this->find($id);
		if($input['reg_win_id'] != null){
			$eventMatches->reg_win_id = $input['reg_win_id'];
			$eventMatches->status = 'C';
			$eventMatches->endDate = Carbon::now();
			$eventMatches->save();
			$this->updateNextReg($id, $input['reg_win_id']);
		}
		return $eventMatches;

	}

	private function updateNextReg($id, $previewWinnerId)
	{
		$eventMatches = EventMatches::where('previes_mate_id1', $id)
			->orWhere('previes_mate_id2', $id)->get();
		if($eventMatches->count() > 0) {
			foreach ($eventMatches as $eventMatch) {
				if ($eventMatch->reg_one_id == null) {
					$eventMatch->reg_one_id = $previewWinnerId;
				} elseif ($eventMatch->reg_two_id == null) {
					$eventMatch->reg_two_id = $previewWinnerId;
				}
				$eventMatch->save();
			}
		}
	}

	public function getMatchesByEventId($id)
	{
		$match = EventMatches::with([
			'entry:id,name,gender_code',
			'belt:id,name',
			'age:id,start_age,end_age',
			'weight:id,weight',
			'regOne:id,member_id',
			'regTwo:id,member_id',
			'regOne.member:id,firstname,lastname',
			'regTwo.member:id,firstname,lastname'
		])->find($id); // use find() instead of get()

		if (!$match) {
			return collect(); // or throw exception / return empty if not found
		}

		Log::info('Event Match:', ['event_match' => $match->toArray()]);

		// Return combined registrations (remove nulls)
		return collect([
			$match->regOne,
			$match->regTwo
		])->filter();
	}

	public function generateMatches($event_id, $input)
	{
		// Step 1: Retrieve the event brackets
		$eventBrackets = DB::table('uq_event_brackets')
			->where('event_id', $event_id)
			->where('entry_id', $input['entry_id'])
			->where('entry_age_id', $input['entry_age_id'])
			->where('entry_weight_id', $input['entry_weight_id'])
			->get();
		$eventMatches = [];

		// Step 2: Save the brackets as initial matches
		foreach ($eventBrackets as $bracket) {
			// Check if the match already exists
			$existingMatch = EventMatches::where('event_id', $bracket->event_id)
				->where('entry_id', $bracket->entry_id)
				->where('entry_age_id', $bracket->entry_age_id)
				->where('entry_belt_id', $bracket->entry_belt_id)
				->where('entry_weight_id', $bracket->entry_weight_id)
				->where('reg_one_id', $bracket->reg_one_id)
				->where('reg_two_id', $bracket->reg_two_id)
				->first();
			Log::info('Event Brackets:', ['existing_match' => $existingMatch->toArray()]);

			if ($existingMatch) {
				$eventMatches[] = $existingMatch; // Add the existing match to the list
				continue; // Skip creating a duplicate match
			}

			// Create a new match if it doesn't exist
			$match = new EventMatches();
			$match->event_id = $bracket->event_id;
			$match->entry_id = $bracket->entry_id;
			$match->entry_age_id = $bracket->entry_age_id;
			$match->entry_belt_id = $bracket->entry_belt_id;
			$match->entry_weight_id = $bracket->entry_weight_id;
			$match->reg_one_id = $bracket->reg_one_id;
			$match->reg_two_id = $bracket->reg_two_id;
			$match->status = 'P'; // Pending status
			if($bracket->reg_one_id == null || $bracket->reg_two_id == null){
				$match->reg_win_id = $bracket->reg_one_id ?? $bracket->reg_two_id;
				$match->status = 'C'; // Complete status
			}
			$match->save();

			$eventMatches[] = $match;
		}
		Log::info('Event Brackets:', ['event_matches' => $eventMatches]);
		$allMatches = $eventMatches; // Store all matches (current and future)
		while (count($eventMatches) > 1) {
			$futureMatches = [];
			$matchCount = count($eventMatches);

			for ($i = 0; $i < $matchCount; $i += 2) {
				if (isset($eventMatches[$i + 1])) {
					// Check if the future match already exists
					$existingFutureMatch = EventMatches::where('event_id', $event_id)
						->where('entry_id', $input['entry_id'])
						->where('entry_age_id', $input['entry_age_id'])
						->where('entry_belt_id', $input['entry_belt_id'])
						->where('entry_weight_id', $input['entry_weight_id'])
						->where('previes_mate_id1', $eventMatches[$i]->id)
						->where('previes_mate_id2', $eventMatches[$i + 1]->id)
						->first();

					if ($existingFutureMatch) {
						$futureMatches[] = $existingFutureMatch; // Add the existing match to the list
						$allMatches[] = $existingFutureMatch;
						continue; // Skip creating a duplicate match
					}

					// Create a new future match if it doesn't exist
					$futureMatch = new EventMatches();
					$futureMatch->event_id = $event_id;
					$futureMatch->entry_id = $input['entry_id'];
					$futureMatch->entry_age_id = $input['entry_age_id'];
					$futureMatch->entry_belt_id = $input['entry_belt_id'];
					$futureMatch->entry_weight_id = $input['entry_weight_id'];
					$futureMatch->previes_mate_id1 = $eventMatches[$i]->id;
					$futureMatch->previes_mate_id2 = $eventMatches[$i + 1]->id;
					$futureMatch->status = 'P'; // Pending status
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
					$allMatches[] = $futureMatch; // Add to the list of all matches
				}
			}

			$eventMatches = $futureMatches; // Set future matches as the current matches for the next round
		}
		
		Log::info('Event Brackets:', ['all_matches' => $allMatches]);
		return [
			'all_matches' => $allMatches,
			'final_match' => $eventMatches[0] ?? null, // The last match
		];
	}
}
