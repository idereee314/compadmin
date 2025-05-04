<?php namespace event;

use core\sessions\Sessions;
use Illuminate\Http\Request;

use event\EventMate;
use event\EventDays;
use event\EventRegistration;
use event\EloquentEventMatchesRepository;

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

class EloquentEventConfigDaysRepository implements EventConfigDaysRepository {

	public function find($id)
	{
		return EventDays::find($id);
	}

	public function create($input)
	{
		$eventDays = new EventDays;
		$eventDays->event_id = $input['event_id'];
		$eventDays->reg_start_date = $input['reg_start_date'];
		$eventDays->reg_end_date = $input['reg_end_date'];
		$eventDays->save();
		return $eventDays;
	}

 	public function update($id, $input)
	{
		$eventDays = $this->find($id);
		$eventDays->event_id = $input['event_id'];
		$eventDays->reg_start_date = $input['reg_start_date'];
		$eventDays->reg_end_date = $input['reg_end_date'];
		$eventDays->save();
		return $eventDays;
	}

	public function delete($id)
	{
		$eventDays = $this->find($id);
		$eventDays->delete();
	}

	public function getMatByEventId($event_id)
	{
		$eventDays = new EventDays;
		return $eventDays->getMatByEventId($event_id);
	}

    public function generateMatAndDays($event_id, $mat, $start_date, $end_date)
	{
		$currentDate = Carbon::parse($start_date);
		$endDate = Carbon::parse($end_date);
		$item_no = 1;
		while ($currentDate->lte($endDate)) {
			$eventDay = new EventDays;
			$eventDay->event_id = $event_id;
			$eventDay->item_no = $item_no;
			$eventDay->start_date = $currentDate->toDateString();
			$eventDay->save();
			$this->addMatoDays($event_id, $mat, $eventDay->id);
			$currentDate->addDay();
			$item_no++;
		}
	}

	private function addMatoDays($event_id, $mat, $day_id)
	{
		$mat = (int) $mat; // Ensure $mat is an integer

		foreach (range(0, $mat) as $singleMat) {
			$eventMate = new EventMate;
			$eventMate->event_id = $event_id;
			$eventMate->mate_no = $singleMat; // Insert each mat as a number from 1 to n
			$eventMate->day_id = $day_id;
			$eventMate->total_hour = 0;
			$eventMate->save();
		}
	}

	public function saveBracket($event_id, $day_id, $mat_id, $bracket, $total_hour)
	{
		Log::info('saveBracket', [
			'event_id' => $event_id,
			'day_id' => $day_id,
			'mat_id' => $mat_id,
			'bracket' => $bracket,
			'total_hour' => $total_hour,
		]);
		EventMateBracket::updateOrCreate(
			[
				'event_id' => $event_id,
				'day_id' => $day_id,
				'mat_id' => $mat_id,
				'entry_id' => $bracket['entry_id'],
				'entry_belt_id' => $bracket['entry_belt_id'],
				'entry_age_id' => $bracket['entry_age_id'],
				'entry_weight_id' => $bracket['entry_weight_id'],
				'total_hour' => $total_hour,
			],
			[
				'created_at' => now(),
				'updated_at' => now(),
			]
		);
	}

	public function resetMateBracker($event_id, $day_id, $mat_id)
	{
		Log::info('resetMateBracker', [
			'event_id' => $event_id,
			'day_id' => $day_id,
			'mat_id' => $mat_id,
		]);
		return DB::table('uq_comp.uq_event_mate_brackets')
        ->where('event_id', $event_id)
        ->where('day_id', $day_id)
        ->where('mat_id', $mat_id)
        ->delete();
	}

	public function dictData($event_id)
	{
		return DB::table('uq_comp.uq_event_mate_brackets')
        ->where('event_id', $event_id)
        ->where('day_id', $day_id)
        ->where('mat_id', $mat_id)
        ->delete();
	}


	public function getMatchByGroup(Request $request, $eventId)
	{
		Log::info('Event ID:', ['event_id' => $eventId]);
		if (!$eventId) {
			return [];
		}

		// Retrieve filter inputs
		$mat = (int) $request->input('mat');
		$age = (int) $request->input('age');
		$day = (int) $request->input('day');
		$gender = (int) $request->input('gender');
		$weight = (int) $request->input('weight');
		Log::info($request->all());

		// Retrieve EventDays with related mates, matches, and brackets
		$regs = EventDays::with([
			'mates' => function ($query) use ($eventId, $mat, $age, $gender, $weight) {
				$query->select('day_id', 'event_id', 'mate_no', 'total_hour', 'id')
					  ->where('event_id', $eventId)
					  ->when($mat, function ($q) use ($mat) {
						  $q->where('id', $mat);
					  })
					  ->with([
						  'matches' => function ($subQuery) use ($eventId, $age, $gender, $weight) {
							  $subQuery->select('id', 'mat_id', 'start_time', 'end_time', 'status', 'entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id')
									   ->where('event_id', $eventId)
									   ->when($age, function ($q) use ($age) {
										   $q->where('entry_age_id', $age);
									   })
									   ->when($gender, function ($q) use ($gender) {
										   $q->whereHas('entry', function ($entryQuery) use ($gender) {
											   $entryQuery->where('gender_code', $gender);
										   });
									   })
									   ->when($weight, function ($q) use ($weight) {
										   $q->where('entry_weight_id', $weight);
									   })
									   ->with([
										   'entry:id,name,duration'
									   ]);
						  }
					  ]);
			}
		])
		->when($day, function ($query) use ($day) {
			$query->where('id', $day);
		})
	->where('event_id', $eventId)
		->selectRaw('ROW_NUMBER() OVER (ORDER BY start_date ASC) as day, *')
		->get();

		foreach ($regs as $day) {
			foreach ($day->mates as $mate) {
				foreach ($mate->matches as $match) {
					// Dynamically load brackets for each match
					$brackets = $match->brackets()
						->select('id', 'entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id', 'reg_one_id', 'reg_two_id', 'reg_win_id',  'status')
						->where('entry_belt_id', $match->entry_belt_id)
						->where('entry_age_id', $match->entry_age_id)
						->where('entry_weight_id', $match->entry_weight_id)
						->where('event_id', $eventId)
						->with([
							'entry:id,name,gender_code', // Include entry relationship
							'belt:id,name', // Include belt relationship
							'age:id,start_age,end_age', // Include age relationship
							'weight:id,weight', // Include weight relationship
							'regOne:id,member_id', // Include regOne relationship
							'regTwo:id,member_id', // Include regTwo relationship
							'regOne.member:id,firstname,lastname', // Include member relationship for regOne
							'regTwo.member:id,firstname,lastname',  // Include member relationship for regTwo
							'regWin.member:id,firstname,lastname'  // Include member relationship for regTwo
						])
						->get();
					$match->brackets = $brackets;
				}
			}
		}

		return $regs;
	}

	public function getEventEntries($eventId){
		if (!$eventId) {
			return [];
		}
	
		// Fetch and group EventRegistration data
		return EventRegistration::selectRaw('entry_id, entry_belt_id, entry_age_id, entry_weight_id, COUNT(*) as total')
			->where('event_id', $eventId)
			->where('status', Config::get('smart.event_registration_status')['approved'])
			->groupBy('entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id')
			->with([
				'entry:id,name,gender_code', // Include entry relationship
				'belt:id,name', // Include belt relationship
				'age:id,start_age,end_age', // Include age relationship
				'weight:id,weight' // Include weight relationship
			])
			->get();
	}
}