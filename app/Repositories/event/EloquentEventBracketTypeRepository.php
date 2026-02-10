<?php namespace event;

use event\EventBracketType;
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

class EloquentEventBracketTypeRepository implements EventBracketTypeRepository {

	public function all()
	{
		return EventBracketType::all();
	}

	public function find($id)
	{
		return EventBracketType::find($id);
	}

	public function activeList()
	{
	    return EventBracketType::where('is_active', true)->get();
	}


}
