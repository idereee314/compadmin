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
		$dates = explode("/", @$input['reg_date']);
		$eventConfig = new EventConfig;
		$eventConfig->event_id = $input['event_id'];
		$eventConfig->reg_start_date = @$dates[0];
		$eventConfig->reg_end_date = @$dates[1];
		$eventConfig->org_types = '{'.implode(", ", @$input['org_types']).'}';
		$eventConfig->is_active = @$input['is_active'] ? $input['is_active'] : false;
		$eventConfig->payment_final_date = @$input['reg_payment_date'];
		$eventConfig->update_final_date = @$input['reg_update_date'];
		$eventConfig->sport_id = @$input['sport_id'];
		$eventConfig->is_team = @$input['is_team'] ? $input['is_team'] : false;
		
		// $eventConfig->max_entry = @$input['max_entry'];

		$eventConfig->save();
		return $eventConfig;
	}

 	public function update($id, $input)
	{
		$dates = explode("/", @$input['reg_date']);
		$eventConfig = $this->find($id);
		$eventConfig->event_id = $input['event_id'];
		$eventConfig->reg_start_date = @$dates[0];
		$eventConfig->reg_end_date = @$dates[1];
		$eventConfig->org_types = '{'.implode(", ", @$input['org_types']).'}';
		$eventConfig->is_active = @$input['is_active'] ? $input['is_active'] : false;
		$eventConfig->payment_final_date = @$input['reg_payment_date'];
		$eventConfig->update_final_date = @$input['reg_update_date'];
		$eventConfig->sport_id = @$input['sport_id'];
		$eventConfig->is_team = @$input['is_team'] ? $input['is_team'] : false;
		
		// dd($eventConfig);
		// $eventConfig->max_entry = @$input['max_entry'];
		
		$eventConfig->save();
		return $eventConfig;
	}

	public function delete($id)
	{
		$eventConfig = $this->find($id);
		$eventConfig->delete();
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

	public function getEventConfig()
	{
		$comps = EventConfig::selectRaw('uq_event_config.*, rti_event.name')->join('rti_event', 'rti_event.id', '=', 'uq_event_config.event_id')->get();
		return $comps;
	}

	public function copyEventConfig($eventConfigId, $input)
    {
		$dates = explode("/", @$input['reg_date']);
        $eventConfig = $this->find($eventConfigId);

        $eventConfigCopy = $eventConfig->replicate();
        $eventConfigCopy->event_id = $input['event_id'];
        $eventConfigCopy->reg_start_date = @$dates[0];
		$eventConfigCopy->reg_start_date = @$dates[1];

        $eventConfigCopy->save();
        return $eventConfigCopy;
    }

	public function getDatatableList($searchData)
    {
		$qry = EventConfig::select('*')->with('event:id,name', 'event.users')->withCount(['entries', 'configBelts', 'configAges', 'configWeights']);
		// dd(Auth::user());
		if(Auth::user()->roles->first()->code == 'admin' || Auth::user()->roles->first()->code == 'event')
		{
			$data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('event') && $searchData->get('event') !== null)
                {
                    $qry->where('uq_event_registration.event_id', $searchData->get('event'));
                }
            })
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
			->editColumn('entries_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-2">'.$qry->entries_count.'</a>';
			})
			->editColumn('config_belts_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-3">'.$qry->config_belts_count.'</a>';
			})
			->editColumn('config_ages_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-4">'.$qry->config_ages_count.'</a>';
			})
			->editColumn('config_weights_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-5">'.$qry->config_weights_count.'</a>';
			})
            ->addColumn('action', function ($qry) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.event_config'), Config::get('permission.editable'));
				if($permissionEdit)
				{
					if($qry->event->users->contains(Auth::user()->id) || Auth::user()->roles->first()->code == 'admin')
					{
						$actionHtml = "";
						if(Carbon\Carbon::parse($qry->reg_end_date) >= Carbon\Carbon::now())
						{
							$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="'.route('event.config.edit', $qry->id).'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
							$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm delete mr-3" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
						}
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm copy mr-3" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_copy').'"><i class="far fa-copy"></i></li>';
						return $actionHtml;
					}
				}
            })->rawColumns(['action', 'entries_count', 'config_belts_count', 'config_ages_count', 'config_ages_count', 'config_weights_count'])
            ->make(true);

        	return $data;
		}
		elseif(Auth::user()->roles->first()->code == 'staff' || Auth::user()->roles->first()->code == '')
		{
			$data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('event') && $searchData->get('event') !== null)
                {
                    $qry->where('uq_event_registration.event_id', $searchData->get('event'));
                }
            })
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
			->editColumn('entries_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-2">'.$qry->entries_count.'</a>';
			})
			->editColumn('config_belts_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-3">'.$qry->config_belts_count.'</a>';
			})
			->editColumn('config_ages_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-4">'.$qry->config_ages_count.'</a>';
			})
			->editColumn('config_weights_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-configid="'.$qry->id.'" data-tabid="tab1-5">'.$qry->config_weights_count.'</a>';
			})
            ->addColumn('action', function ($qry) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.event_config'), Config::get('permission.editable'));
				if($permissionEdit)
				{
					if($qry->event->users->contains(Auth::user()->id) || Auth::user()->roles->first()->code == 'admin')
					{
						$actionHtml = "";
						if(Carbon\Carbon::parse($qry->reg_end_date) >= Carbon\Carbon::now())
						{
							$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="'.route('event.config.edit', $qry->id).'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
							$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm delete mr-3" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
						}
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm copy mr-3" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_copy').'"><i class="far fa-copy"></i></li>';
						return $actionHtml;
					}
				}
            })->rawColumns(['action', 'entries_count', 'config_belts_count', 'config_ages_count', 'config_ages_count', 'config_weights_count'])
            ->make(true);

        	return $data;
		}
	}

	public function getEventConfigByPage($perPage = 10, $searchData = null)
	{
		$qry = EventConfig::select('uq_event_config.*')
			->with(['event:id,name,description,event_date,due_date', 'event.picturesMobileCover:event_id,dir_url,url', 'event.registration.member:id,profile_url,firstname,lastname'])
			->withCount(['registration', 'registration as status_approved' => function ($q) {
				$q->where('uq_event_registration.status', @Config::get('smart.event_registration_status')['approved']);
			}])
			->join('uq_event_user', 'uq_event_user.event_id', '=', 'uq_event_config.event_id')
			->where('uq_event_user.user_id', '=', Auth::user()->id);

		$eventConfig = $qry->orderBy('created_at', 'desc')->paginate($perPage);
		return $eventConfig->toJson();
	}

	public function findByEventId($eventId)
	{
		$config = "";
		if($eventId)
		{
			$qry = EventConfig::where('event_id', $eventId);
			$config = $qry->first();
		}

		return $config;
	}
}
