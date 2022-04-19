<?php namespace event;

use event\EventUser;
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

class EloquentEventUserRepository implements EventUserRepository {

	public function all()
	{
		return EventUser::all();
	}

	public function find($id)
	{
		return EventUser::find($id);
	}

	public function create($input)
	{

	}

	public function update($id, $input)
	{

	}

	public function delete($id)
	{
		$eventUser = $this->find($id);

		$eventUser->delete();
	}
}
