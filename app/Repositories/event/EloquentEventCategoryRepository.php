<?php namespace event;

use event\EventCategory;
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

class EloquentEventCategoryRepository implements EventCategoryRepository {

	public function all()
	{
		return EventCategory::all();
	}

	public function find($id)
	{
		return EventCategory::find($id);
	}

}
