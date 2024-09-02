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

	public function find($id)
	{
		return Event::find($id);
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

	public function getEventByPage($perPage = 10, $searchData = null)
	{

		$isAdmin = DB::table('uniqdb.uq_comp.uq_compad_user_role')
            ->join('uniqdb.uq_comp.uq_compad_role', 'uniqdb.uq_comp.uq_compad_role.id', '=', 'uniqdb.uq_comp.uq_compad_user_role.role_id')
            ->where('uniqdb.uq_comp.uq_compad_user_role.user_id', '=', Auth::user()->id)
            ->whereIn('uniqdb.uq_comp.uq_compad_role.code', ['admin', 'mjjf'])
            ->exists();

		if ($isAdmin) {
		    $qry = Event::selectRaw('rti_event.id, rti_event.name, rti_event.description, rti_event.event_date, rti_event.due_date, uq_event_config.reg_start_date, uq_event_config.reg_end_date')
		        ->join('uq_event_config', 'uq_event_config.event_id', '=', 'rti_event.id')
		        ->with(['picturesMobileCover:event_id,dir_url,url', 'members:id,profile_url,firstname,lastname,gender_code'])
		        ->withCount(['registration', 'registration as status_approved' => function ($q) {
		            $q->where('uq_event_registration.status', @Config::get('smart.event_registration_status')['approved']);
		        }])
				->withCount(['eventRefundRequest', 'eventRefundRequest as refund_status_approved' => function ($q) {
		            $q->where('uq_event_refund_request.status', @Config::get('smart.event_refund_request_status')['approved']);
		        }])
				->orderBy('rti_event.event_date', 'desc');
		} else {
		    $qry = Event::selectRaw('rti_event.id, rti_event.name, rti_event.description, rti_event.event_date, rti_event.due_date, uq_event_config.reg_start_date, uq_event_config.reg_end_date')
		        ->join('uq_event_config', 'uq_event_config.event_id', '=', 'rti_event.id')
		        ->with(['picturesMobileCover:event_id,dir_url,url', 'members:id,profile_url,firstname,lastname,gender_code'])
		        ->withCount(['registration', 'registration as status_approved' => function ($q) {
		            $q->where('uq_event_registration.status', @Config::get('smart.event_registration_status')['approved']);
		        }])
		        ->join('uq_event_user', 'uq_event_user.event_id', '=', 'rti_event.id')
		        ->where('uq_event_user.user_id', '=', Auth::user()->id)
		        ->orderBy('rti_event.event_date', 'desc');
		}


	    $eventConfig = $qry->orderBy('uq_event_config.created_at', 'desc')->paginate($perPage);
	    return json_encode($eventConfig);
	}

	public function getEventBySportPage($perPage = 10, $sportId = null)
	{
	    $isAdmin = DB::table('uniqdb.uq_comp.uq_compad_user_role')
	        ->join('uniqdb.uq_comp.uq_compad_role', 'uniqdb.uq_comp.uq_compad_role.id', '=', 'uniqdb.uq_comp.uq_compad_user_role.role_id')
	        ->where('uniqdb.uq_comp.uq_compad_user_role.user_id', '=', Auth::user()->id)
	        ->whereIn('uniqdb.uq_comp.uq_compad_role.code', ['admin', 'mjjf'])
	        ->exists();
	
	    $qry = Event::selectRaw('rti_event.id, rti_event.name, rti_event.description, rti_event.event_date, rti_event.due_date, uq_event_config.reg_start_date, uq_event_config.reg_end_date')
	        ->join('uq_event_config', 'uq_event_config.event_id', '=', 'rti_event.id')
	        ->with(['picturesMobileCover:event_id,dir_url,url', 'members:id,profile_url,firstname,lastname,gender_code'])
	        ->withCount(['registration', 'registration as status_approved' => function ($q) {
	            $q->where('uq_event_registration.status', @Config::get('smart.event_registration_status')['approved']);
	        }])
	        ->withCount(['eventRefundRequest', 'eventRefundRequest as refund_status_approved' => function ($q) {
	            $q->where('uq_event_refund_request.status', @Config::get('smart.event_refund_request_status')['approved']);
	        }])
	        ->orderBy('rti_event.event_date', 'desc');
		
	    if (!$isAdmin) {
	        $qry->join('uq_event_user', 'uq_event_user.event_id', '=', 'rti_event.id')
	            ->where('uq_event_user.user_id', '=', Auth::user()->id);
	    }
	
	    if ($sportId) {
	        $qry->where('uq_event_config.sport_id', $sportId);
	    }
	
	    $eventConfig = $qry->orderBy('uq_event_config.created_at', 'desc')->paginate($perPage);
	    return json_encode($eventConfig);
	}


}
