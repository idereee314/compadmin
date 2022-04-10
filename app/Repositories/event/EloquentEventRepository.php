<?php namespace event;

use event\Event;
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

class EloquentEventRepository implements EventRepository {

	public function all()
	{
		return Event::all();
	}

	public function searchEvent($data)
	{
		   //DB::enableQueryLog();
		   $event = "";
		   $qry = Event::selectRaw("rti_event.id, rti_event.name, rti_event.event_date, rti_event.due_date");
   
		   if(!empty(@$data))
		   {
			   $qry->whereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$data).'%'))
			   	   ->where('rti_event.event_date', '>', Carbon\Carbon::now()->toDateTimeString());
		   }
		   $event = $qry->get();
   
		   /*
		   $queries = DB::getQueryLog();
		   dd($queries);
		   */
		   return $event;
	}

	
}
