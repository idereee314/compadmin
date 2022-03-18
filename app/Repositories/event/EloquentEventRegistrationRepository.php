<?php namespace event;

use event\EventRegistration;
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

class EloquentEventRegistrationRepository implements EventRegistrationRepository {

	public function all()
	{
		return EventRegistration::all();
	}

	public function allPaginate()
	{
		return EventRegistration::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventRegistration::find($id);
	}

	public function create($input)
	{
		$eventRegistraion = new EventRegistration;

		$eventRegistraion->member_id = $input['member_id'];
		$eventRegistraion->event_id = $input['event_id'];
		$eventRegistraion->entry_id = $input['entry_id'];
		$eventRegistraion->entry_age_id = $input['entry_age_id'];
		$eventRegistraion->entry_belt_id = $input['entry_belt_id'];
		$eventRegistraion->entry_weight_id = @$input['entry_weight_id'];
		$eventRegistraion->academy_id = @$input['academy_id'];
		$eventRegistraion->status = @$input['status'];

		$eventRegistraion->save();
		return $eventRegistraion;
	}

 	public function update($id, $input)
	{
		$eventRegistraion = $this->find($id);
		$eventRegistraion->entry_id = $input['entry_id'];
		$eventRegistraion->entry_age_id = $input['entry_age_id'];
		$eventRegistraion->entry_belt_id = $input['entry_belt_id'];
		$eventRegistraion->entry_weight_id = @$input['entry_weight_id'];
		$eventRegistraion->academy_id = @$input['academy_id'];
		$eventRegistraion->status = @$input['status'];

		$eventRegistraion->save();
		return $eventRegistraion;
	}

	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventRegistration::selectRaw('uq_event_registration.*, uq_event_config.reg_start_date, uq_event_config. reg_end_date')
			->join('uq_event_config', 'uq_event_config.event_id', '=', 'uq_event_registration.event_id')
			->with(['event:id,name,description,event_date,due_date', 'member:id,register_number,contact_phone,firstname,lastname', 'entry:id,name', 'age:id,start_age,end_age', 'belt:id,name', 'weight:id,weight']);

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('event') && $searchData->get('event') !== null)
                {
                    $qry->where('event_id', $searchData->get('event'));
                }

                if($searchData->has('entry') && $searchData->get('entry') !== null)
                {
					$qry->where('entry_id', $searchData->get('entry'));
				}

				if($searchData->has('entryAge') && $searchData->get('entryAge') !== null)
                {
					$qry->where('entry_age_id', $searchData->get('entryAge'));
				}

				if($searchData->has('entryBelt') && $searchData->get('entryBelt') !== null)
                {
					$qry->where('entry_belt_id', $searchData->get('entryBelt'));
				}

				if($searchData->has('entryWeight') && $searchData->get('entryWeight') !== null)
                {
					$qry->where('entry_weight_id', $searchData->get('entryWeight'));
				}

				if($searchData->has('status') && $searchData->get('status') !== null)
                {
					$qry->where('status', $searchData->get('status'));
				}

                if($searchData->has('member') && $searchData->get('member') !== null)
                {
					$qry->whereHas('member', function($q){
						$q->whereRaw("LOWER(register_number) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'))
						->orWhereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'))
						->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'));
					});
                }
            })
			->editColumn('status', function($qry)
			{
				$status = '<span class="label label-lg font-weight-bold label-light-'.@Config::get('smart.event_registeation_status_class')[$qry->status].' label-inline">'.@Config::get('enums.event_registeation_status')[$qry->status].'</span>';
				return $status;
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($qry) {

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($qry->status == @Config::get('smart.event_registeation_status')['created'])
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link edit" href="javascript:;" data-registrationid="'.$qry->id.'"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link delete" href="javascript:;" data-registrationid="'.$qry->id.'"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				} 
				
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action', 'status'])
            ->make(true);

        return $data;
	}
}
