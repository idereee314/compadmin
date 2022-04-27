<?php namespace event;

use event\EventAward;
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

class EloquentEventAwardRepository implements EventAwardRepository {

	public function all()
	{
		return EventAward::all();
	}

	public function allPaginate()
	{
		return EventAward::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventAward::find($id);
	}

	public function create($input)
	{
		$eventAward = new EventAward;
		$eventAward->event_registration_id = $input['event_registration_id'];
		$eventAward->member_id = @$input['member_id'];
		$eventAward->place_number = $input['place_number'];

		$eventAward->save();
		return $eventAward;
	}

 	public function update($id, $input)
	{
		$eventAward = $this->find($id);
		$eventAward->place_number = $input['place_number'];

		$eventAward->save();
		return $eventAward;
	}

	public function delete($id)
	{
		$eventAward = $this->find($id);
		$eventAward->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventAward::selectRaw('uq_event_award.*')
			->with(['eventRegistration.event:id,name,description,event_date,due_date', 'member:id,register_number,contact_phone,firstname,lastname,profile_url,id_url', 'eventRegistration.entry:id,name', 'eventRegistration.age:id,start_age,end_age', 'eventRegistration.belt:id,name', 'eventRegistration.weight:id,weight', 'eventRegistration.academy:id,name,is_other']);

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('event') && $searchData->get('event') !== null)
                {
                    $qry->where('uq_event_registration.event_id', $searchData->get('event'));
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

				if($searchData->has('gender') && $searchData->get('gender') !== null)
                {
					$qry->whereHas('member', function($q) use($searchData){
						$q->where('gender_code', $searchData->get('gender'));
					});				
				}

				if($searchData->has('status') && $searchData->get('status') !== null)
                {
					$qry->where('status', $searchData->get('status'));
				}

				if($searchData->has('academy') && $searchData->get('academy') !== null)
                {
					$qry->where('academy_id', $searchData->get('academy'));
					/*
					$qry->whereHas('academy', function($q) use($searchData){
						$q->where("name", $searchData->get('academy'));
					})
					->orWhereRaw("LOWER(academy_name) like ?", array('%'.mb_strtolower($searchData->get('academy')).'%'));
					*/
				}
				
				if($searchData->has('date') && !empty(array_filter($searchData->get('date'))))
                {
					$qry->whereBetween('uq_event_registration.created_at', $searchData->get('date'));
				}

                if($searchData->has('member') && $searchData->get('member') !== null)
                {
					$qry->whereHas('member', function($q) use($searchData){
						$q->whereRaw("LOWER(register_number) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'))
						->orWhereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'))
						->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'))
						->orWhereRaw("LOWER(contact_phone) like ?", array('%'.mb_strtolower($searchData->get('member')).'%'));
					});
                }
            })
			->editColumn('status', function($qry)
			{
				$status = '<span class="label label-lg font-weight-bold label-light-'.@Config::get('smart.event_registration_status_class')[$qry->status].' label-inline">'.@Config::get('enums.event_registration_status')[$qry->status].'</span>';
				return $status;
			})
			->addColumn('profile_url', function ($qry) {
				$src = "";
				if ($qry->member->profile_url) {
					$src = '<a href="javascript:;" class="show-image" data-id="'.$qry->member->id.'" data-type="profile"><img class="align-self-end" alt="Profile" src="'.\Storage::disk('s3')->url($qry->member->profile_url).'" style="max-width: 50px;"></a>';
				}
				return $src;
			})
			->editColumn('id_photo', function ($qry) {
				if ($qry->member->id_url) {
					return '<a class="btn btn-light show-image" data-id="'.$qry->member->id.'" data-type="id"><i class="far fa-eye ml-1"></i></a>';
				}
				return "";
			})
			->editColumn('academy_name', function($qry)
			{
				if($qry->academy->is_other == 1) {
					return $qry->academy_name;	
				} else {
					return $qry->academy->name;
				}
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($qry) {
				$actionHtml = "";
				$actionHtml .= '<div class="dropdown dropdown-inline">';
					$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
						$actionHtml .= '<i class="la la-cog"></i>';
					$actionHtml .= '</a>';
					$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
						$actionHtml .= '<ul class="nav nav-hoverable flex-column">';;
							$actionHtml .= '<li class="nav-item"><a class="nav-link award" href="javascript:;" data-registrationid="'.$qry->id.'"><i class="nav-icon la la-award"></i><span class="nav-text">Байр эзлүүлэх</span></a></li>';
						$actionHtml .= '</ul>';
					$actionHtml .= '</div>';
				$actionHtml .= '</div>';
				$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon edit" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
				if($qry->status == @Config::get('smart.event_registration_status')['created'])
				{
					$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon delete" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
				}
				return $actionHtml;

            })->rawColumns(['action', 'status', 'profile_url', 'id_photo', 'academy_name'])
            ->make(true);

        return $data;
	}

	public function getEventRegStatusCount($eventId)
	{
		$count = "";
		if(@$eventId)
		{
			$qry = EventAward::selectRaw('status, count(*) as total')->where('event_id', $eventId)->groupBy('status');
			$count = $qry->get();
		}

		return $count;
	}
}
