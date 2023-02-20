<?php namespace event;

use event\EventTeamRegistrationStatus;
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

class EloquentEventTeamRegistrationStatusRepository implements EventTeamRegistrationStatusRepository {

	public function all()
	{
		return EventTeamRegistrationStatus::all();
	}

	public function find($id)
	{
		return EventTeamRegistrationStatus::find($id);
	}

	public function create($input)
	{
		$status = new EventTeamRegistrationStatus;

		$status->team_registration_id = $input['team_registration_id'];
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
