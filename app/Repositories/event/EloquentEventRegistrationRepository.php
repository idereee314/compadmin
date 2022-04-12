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
		$eventRegistraion->academy_name = @$input['academy_name'];
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
		$eventRegistraion->academy_name = @$input['academy_name'];
		$eventRegistraion->status = @$input['status'];
		$eventRegistraion->is_weight_checked = @$input['is_weight_checked'] ? true: false ;

		$eventRegistraion->save();
		return $eventRegistraion;
	}

	public function delete($id)
	{
		$eventRegistraion = $this->find($id);

		$eventRegistraion->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventRegistration::selectRaw('uq_event_registration.*, uq_event_config.reg_start_date, uq_event_config. reg_end_date')
			->join('uq_event_config', 'uq_event_config.event_id', '=', 'uq_event_registration.event_id');

			if($searchData->has('event') && $searchData->get('event') !== null)
			{
				$qry->where('uq_event_registration.event_id', $searchData->get('event'));
			}

			$qry->with(['entry:id,name', 'age:id,start_age,end_age', 'belt:id,name', 'weight:id,weight', 'academy:id,name,is_other']);

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                
                if($searchData->has('entry') && $searchData->get('entry') !== null)
                {
					$qry->where('uq_event_registration.entry_id', $searchData->get('entry'));
				}

				if($searchData->has('entryAge') && $searchData->get('entryAge') !== null)
                {
					$qry->where('uq_event_registration.entry_age_id', $searchData->get('entryAge'));
				}

				if($searchData->has('entryBelt') && $searchData->get('entryBelt') !== null)
                {
					$qry->where('uq_event_registration.entry_belt_id', $searchData->get('entryBelt'));
				}

				if($searchData->has('entryWeight') && $searchData->get('entryWeight') !== null)
                {
					$qry->where('uq_event_registration.entry_weight_id', $searchData->get('entryWeight'));
				}

				if($searchData->has('gender') && $searchData->get('gender') !== null)
                {
					$qry->whereHas('member', function($q) use($searchData){
						$q->where('gender_code', $searchData->get('gender'));
					});				
				}

				if($searchData->has('is_weight') && $searchData->get('is_weight') !== null)
                {
					$qry->where('is_weight_checked', $searchData->get('is_weight'));			
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
			->setRowAttr([
				'class' => function($qry) {
					return @$qry->is_weight_checked ? 'table-secondary' : '';
				}
			])
			->editColumn('status', function($qry)
			{
				$status = '<span class="label label-lg font-weight-bold label-light-'.@Config::get('smart.event_registeation_status_class')[$qry->status].' label-inline">'.@Config::get('enums.event_registeation_status')[$qry->status].'</span>';
				return $status;
			})
			/*
			->addColumn('profile_url', function ($qry) {
				$src = "";
				if ($qry->member->profile_url) {
					$src = '<a href="javascript:;" class="show-image" data-id="'.$qry->member->id.'" data-type="profile"><img class="align-self-end" alt="Profile" src="'.\Storage::disk('s3')->url($qry->member->profile_url).'" style="max-width: 50px;"></a>';
				}
				return $src;
			})
			*/
			->addColumn('event', function($qry){
				return ' <span class="label label-primary label-dot mr-2"></span><span class="font-weight-bold text-danger">'.$qry->event->name.'</span> /'.$qry->event->event_date.' - '.$qry->event->due_date.'/';
			})
			->addColumn('member', function($qry){
				$member = "";
				$member .= '<div class="d-flex align-items-center">';
                	$member .= '<a href="javascript:;" class="show-image" data-id="'.$qry->member->id.'" data-type="profile"><div class="symbol symbol-50 flex-shrink-0">';
						$member .= '<img src="'.\Storage::disk('s3')->url($qry->member->profile_url).'" alt="Profile">';
					$member .= '</div></a>';
					$member .= '<div class="ml-3">';
						$member .= '<span class="text-dark-75 line-height-sm d-block pb-3" style="white-space: nowrap;">'.$qry->member->lastname.' <strong>'.$qry->member->firstname.'</strong></span>';
                        $member .= '<span class="text-dark-75 line-height-sm d-block pb-2"><i class="la la-address-book"></i>'.$qry->member->register_number.', <i class="la la-phone"></i>'.$qry->member->contact_phone.'</span>';
					$member .= '</div>';
                $member .= '</div>';
				return $member;
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
				if ($qry->member->id_url) {
					$actionHtml .= '<a class="btn btn-icon btn-clean btn-sm mr-3 show-image" data-id="'.$qry->member->id.'" data-type="id" title="'.trans('display.id_photo').'"><i class="far fas fa-paperclip text-warning"></i></a>';
				}
				if($qry->event->due_date <= Carbon\Carbon::now())
				{
					if(@$qry->award)
					{
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 win-place" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.comp_award_place').'"><span class="class="svg-icon svg-icon-md svg-icon-primary">'.@$qry->award->place_number.'</span></a>';
					}
					else
					{
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 win-place" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.comp_award_place').'"><i class="nav-icon la la-award"></i></a>';
					}
				}
				
				if($qry->event->due_date > Carbon\Carbon::now()){
					$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
					if($qry->status == @Config::get('smart.event_registeation_status')['created'])
					{
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm delete" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
					}
				}
				return $actionHtml;

            })->rawColumns(['action', 'status', 'member', 'id_photo', 'event', 'academy_name'])
            ->make(true);

        return $data;
	}

	public function getEventRegStatusCount($eventId)
	{
		$count = "";
		if(@$eventId)
		{
			$qry = EventRegistration::selectRaw('status, count(*) as total')->where('event_id', $eventId)->groupBy('status');
			$count = $qry->get();
		}

		return $count;
	}
}
