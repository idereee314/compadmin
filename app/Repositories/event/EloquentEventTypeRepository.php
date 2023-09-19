<?php namespace event;

use event\EventType;
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

class EloquentEventTypeRepository implements EventTypeRepository {

	public function all()
	{
		return EventType::all();
	}

	public function find($id)
	{
		return EventType::find($id);
	}

}
