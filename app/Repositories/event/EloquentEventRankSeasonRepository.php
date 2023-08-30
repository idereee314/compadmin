<?php namespace event;

use event\EventRankSeason;
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

class EloquentEventRankSeasonRepository implements EventRankSeasonRepository {

	public function all()
	{
		return EventRankSeason::all();
	}

	public function find($id)
	{
		return EventRankSeason::find($id);
	}

}
