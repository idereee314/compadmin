<?php namespace event;

use event\EventSport;
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

class EloquentEventSportRepository implements EventSportRepository {

	public function all()
	{
		return EventSport::all();
	}

	public function find($id)
	{
		return EventSport::find($id);
	}

}
