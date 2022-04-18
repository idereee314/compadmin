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
		$qry = EventConfig::select('*')->with('event:id,name');

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
            ->addColumn('action', function ($qry) {
				$actionHtml = "";
				$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="'.route('event.config.edit', $qry->id).'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
				$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm delete mr-3" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
				$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm copy" href="javascript:;" data-configid="'.$qry->id.'" title="'.trans('display.general_copy').'"><i class="far fa-copy"></i></li>';
				return $actionHtml;

            })->rawColumns(['action'])
            ->make(true);

        return $data;
	}

	public function getEventConfigByPage($perPage = 10, $searchData = null)
	{
		$qry = EventConfig::select('uq_event_config.*')
			->with(['event:id,name,description,event_date,due_date', 'event.picturesMobileCover:event_id,dir_url,url', 'event.registrationTen.member:id,profile_url,firstname,lastname'])
			->withCount(['registration', 'registration as status_approved' => function ($q) {
				$q->where('uq_event_registration.status', @Config::get('smart.event_registeation_status')['approved']);
			}]);

		$eventConfig = $qry->orderBy('created_at', 'desc')->paginate($perPage);
		return $eventConfig->toJson();
	}
}
