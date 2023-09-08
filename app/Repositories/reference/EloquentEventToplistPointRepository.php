<?php namespace reference;

use reference\EventToplistPoint;

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

class EloquentEventToplistPointRepository implements EventToplistPointRepository {

	public function all()
	{
		return EventToplistPoint::all();
	}

	public function find($id)
	{
		return EventToplistPoint::find($id);
	}

	public function create($input)
	{	
		$eventToplistPoint = new EventToplistPoint;
		
		$eventToplistPoint->start_pos = $input['start_pos'];
		$eventToplistPoint->end_pos = $input['end_pos'];
		$eventToplistPoint->point = $input['point'];
		$eventToplistPoint->event_id = $input['event_id'];

		$eventToplistPoint->save();

		return $eventToplistPoint;
	}

	public function update($id, $input)
	{	
		$eventToplistPoint = $this->find($id);
		$eventToplistPoint->start_pos = $input['start_pos'];
		$eventToplistPoint->end_pos = $input['end_pos'];
		$eventToplistPoint->point = $input['point'];
		$eventToplistPoint->event_id = $input['event_id'];

		$eventToplistPoint->save();

		return $eventToplistPoint;
	}

	public function delete($id)
	{
		$eventToplistPoint = $this->find($id);

		$eventToplistPoint->delete();
	}
}
