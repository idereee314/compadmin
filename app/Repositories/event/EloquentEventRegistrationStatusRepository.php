<?php namespace event;

use event\EventRegistrationStatus;
use event\EventPayment;

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

class EloquentEventRegistrationStatusRepository implements EventRegistrationStatusRepository {

	public function all()
	{
		return EventRegistrationStatus::all();
	}

	public function find($id)
	{
		return EventRegistrationStatus::find($id);
	}

	public function create($input)
	{
		$status = new EventRegistrationStatus;

		$status->event_registration_id = $input['event_registration_id'];
		$status->status = $input['status'];

		$status->save();
		return $status;
	}

 	public function update($id, $input)
	{
		$status = $this->find($id);
		$status->status = $input['status'];

		$status->save();
		return $status;
	}
}
