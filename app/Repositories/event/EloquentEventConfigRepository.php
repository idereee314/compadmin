<?php namespace event;

use event\EventConfig;
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

class EloquentEventConfigRepository implements EventConfigRepository {

	public function all()
	{
		return EventConfig::all();
	}

	public function allPaginate()
	{
		return EventConfig::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventConfig::find($id);
	}

	public function create($input)
	{
		$eventConfig = new EventConfig;

		$eventConfig->save();

		return $eventConfig;
	}

 	public function update($id, $input)
	{
		$eventConfig = $this->find($id);

		$eventConfig->save();

		return $eventConfig;
	}

	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

	public function getRegistringComp($date = null)
	{
		$comps = "";
		$qry = EventConfig::select('*');
		if(@$date)
		{
			$qry->whereDate('reg_start_date', '>=', $date)
			->whereDate('reg_end_date', '<=', $date);
		}
		$comps = $qry->get();
		return $comps;
	}
}
