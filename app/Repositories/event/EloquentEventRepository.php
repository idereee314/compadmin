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

	public function create($input)
    {
        $dates = explode(" аас ", $input['dates']);

        $event = new Event;
        $event->name = @$input['name'];
        $event->description = @$input['description'];
        $event->event_date = @$dates[0];
        $event->due_date = @$dates[1];
        $event->period_id = @$input['period_id'];
		$event->status = @$input['status'];

        $event->save();
        return $event;
    }

	public function update($id, $input)
	{
		$dates = explode(" аас ", $input['dates']);

		$event = $this->find($id);
		$event->name = @$input['name'];
		$event->description = @$input['description'];
		$event->event_date = @$dates[0];
		$event->due_date = @$dates[1];
		$event->period_id = @$input['period_id'];
		$event->status = @$input['status'];

		$event->save();
		return $event;
	}

	public function delete($id)
	{
		$event = $this->find($id);
		$event->delete();
	}

	

	public function getDatatableList($searchData)
	{
	    $qry = Event::select('*')
		->join('rti_event_category', 'rti_event_category.event_id', '=', 'rti_event.id')
		->where('rti_event_category.category_id', 357);

	    return DataTables::of($qry)
	        ->filter(function ($qry) use ($searchData) {
				if ($searchData->has('search_date') && !empty($searchData->get('search_date'))) {
					$qry->whereRaw("rti_event.event_date like ?", array('%' . $searchData->get('search_date') . '%'));
				}
				if ($searchData->has('search_name') && !empty($searchData->get('search_name'))) {
	                $qry->whereRaw("LOWER(rti_event.name) like ?", array('%'.mb_strtolower($searchData->get('search_name')).'%'));
	            }
				if($searchData->has('search_status') && !empty($searchData->get('search_status'))) {
	                $qry->where('rti_event.status', $searchData->get('search_status'));
	            }
	        })
	        ->editColumn('status', function($qry) {
	            $status = '<button type="button" class="btn btn-light-' . @Config::get('smart.event_status_class')[$qry->status] . ' btn-sm btn-status" data-registrationid="' . $qry->id . '">' . @Config::get('enums.event_status')[$qry->status] . '</button>';
	            return $status;
	        })
	        ->editColumn('created_at', function($qry) {
	            return $qry->created_at;
	        })
			->addColumn('description', function($qry) {
		        return '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-light-success description" data-eventid="' . @$qry->id . '"><i class="la la-eye"></i></a>';
	        })
			->addColumn('event_details', function($qry) {
				return '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-light-success details" data-eventid="' . @$qry->id . '"><i class="la la-info-circle"></i></a>';
			})
	        ->addColumn('action', function ($qry) {
	            $actionHtml  = '<a class="btn btn-sm btn-clean btn-icon edit" href="javascript:;" data-eventid="'.$qry->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
	            $actionHtml .= '<a class="btn btn-sm btn-clean btn-icon delete" href="javascript:;" data-eventid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></a>';
	            return $actionHtml;
	        })
	        ->rawColumns(['status', 'action', 'description', 'event_details'])
	        ->make(true);
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

		$isAdmin = DB::table('uq_comp.uq_compad_user_role')
            ->join('uq_comp.uq_compad_role', 'uq_comp.uq_compad_role.id', '=', 'uq_comp.uq_compad_user_role.role_id')
            ->where('uq_comp.uq_compad_user_role.user_id', '=', Auth::user()->id)
            ->whereIn('uq_comp.uq_compad_role.code', ['admin', 'mjjf'])
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
	    $isAdmin = DB::table('uq_comp.uq_compad_user_role')
	        ->join('uq_comp.uq_compad_role', 'uq_comp.uq_compad_role.id', '=', 'uq_comp.uq_compad_user_role.role_id')
	        ->where('uq_comp.uq_compad_user_role.user_id', '=', Auth::user()->id)
	        ->whereIn('uq_comp.uq_compad_role.code', ['admin', 'mjjf'])
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
