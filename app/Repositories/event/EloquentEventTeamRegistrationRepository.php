<?php namespace event;

use event\EventTeamRegistration;
use event\EventPayment;
use event\EventBrackets;

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

class EloquentEventTeamRegistrationRepository implements EventTeamRegistrationRepository {

	public function all()
	{
		return EventTeamRegistration::all();
	}

	public function allPaginate()
	{
		return EventTeamRegistration::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventTeamRegistration::find($id);
	}

	public function create($input)
	{
		$eventTeamRegistration = new EventTeamRegistration;

		$eventTeamRegistration->event_id = $input['event_id'];
		$eventTeamRegistration->entry_id = $input['entry_id'];
		$eventTeamRegistration->team_id = @$input['team_id'];
		$eventTeamRegistration->team_name = $input['team_name'];
		$eventTeamRegistration->academy_id = @$input['academy_id'];
		$eventTeamRegistration->academy_name = @$input['academy_name'];
		
		$eventTeamRegistration->save();
		return $eventTeamRegistration;
	}

 	public function update($id, $input)
	{
		$eventTeamRegistration = $this->find($id);
		$eventTeamRegistration->entry_id = $input['entry_id'];
		$team = $eventTeamRegistration->team;
		$team->name = $input['team_id'];
		$eventTeamRegistration->team_name = $input['team_name'];
		$team->save();

		$eventTeamRegistration->academy_id = @$input['academy_id'];
		$eventTeamRegistration->academy_name = @$input['academy_name'];
		//$eventTeamRegistration->status = @$input['status'];
		
		$eventTeamRegistration->save();
		return $eventTeamRegistration;
	}

	public function delete($id)
	{
		$eventTeamRegistration = $this->find($id);

		$eventTeamRegistration->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = EventTeamRegistration::selectRaw('uq_team_registration.*, uq_event_config.reg_start_date, uq_event_config.reg_end_date, (select count(*) from "uq_team_registration_member" where "uq_team_registration"."team_id" = "uq_team_registration_member"."team_id") as "teamathlete_count"')
            ->join('uq_event_config', 'uq_event_config.event_id', '=', 'uq_team_registration.event_id');

			if($searchData->has('event') && $searchData->get('event') !== null)
			{
				$qry->where('uq_team_registration.event_id', $searchData->get('event'));
			}

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {                
				if($searchData->has('reg_id') && $searchData->get('reg_id') !== null)
                {
					$qry->where('uq_team_registration.id', $searchData->get('reg_id'));
				}

				if($searchData->has('status') && $searchData->get('status') !== null)
                {
					$qry->where('status', $searchData->get('status'));
				}

				if($searchData->has('academy') && $searchData->get('academy') !== null)
                {
					$qry->where('academy_id', $searchData->get('academy'));
				}

				if($searchData->has('team') && $searchData->get('team') !== null)
                {
					$qry->where('team_id', $searchData->get('team'));
				}
				
				if($searchData->has('date') && !empty(array_filter($searchData->get('date'))))
                {
					$qry->whereBetween('uq_team_registration.created_at', $searchData->get('date'));
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

				if($searchData->has('amount') && $searchData->get('amount') !== null)
                {
					if($searchData->get('amount') > 0 || $searchData->get('amount') == 0)
					{
						$qry->whereHas('payments', function($q) use($searchData){
							$q->where('amount', $searchData->get('amount'));
						});
					}
					else 
					{
						$qry->whereDoesntHave('payments');
					}
					
				}
            })
			->setRowAttr([
				'class' => function($qry) {
					return @$qry->is_weight_checked ? 'table-success' : '';
				}
			])
			// ->editColumn('team_name', function($qry)
			// {
			// 	return $qry->team->name;
			// })
			->editColumn('entry_name', function($qry)
			{
				return $qry->entry->name;
			})
			->editColumn('status', function($qry)
			{
				$status = '<button type="button" class="btn btn-light-'.@Config::get('smart.event_registration_status_class')[$qry->status].' btn-sm btn-status" data-registrationid="'.$qry->id.'">'.@Config::get('enums.event_registration_status')[$qry->status].'</button>';
				return $status;
			})
			->addColumn('event', function($qry){
				return ' <span class="label label-primary label-dot mr-2"></span><span class="font-weight-bold text-danger">'.$qry->event->name.'</span> /'.$qry->event->event_date.' - '.$qry->event->due_date.'/';
			})
			->editColumn('academy_name', function($qry)
			{
				if($qry->academy->is_other == 1) {
					return $qry->academy_name;	
				} else {
					return $qry->academy->name;
				}
			})
			->editColumn('teamathlete_count', function($qry)
			{
				return '<a href="javascript:;" class="show-count" data-registrationid="'.$qry->id.'">'.$qry->teamathlete_count.'</a>';
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
			// ->addColumn('athlete_average_age', function($qry){
			// 	dd($qry);
				
			// 	return $qry->teamathlete;
			// })
            ->addColumn('action', function ($qry) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.event_registration'), Config::get('permission.editable'));
				$actionHtml = "";
				if($permissionEdit && ($qry->event->users->contains(Auth::user()->id) || Auth::user()->roles->first()->code == 'admin'))
				{
					if($qry->event->due_date <= Carbon\Carbon::now() && $qry->status == @Config::get('smart.event_registration_status')['approved'])
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
					//if($qry->event->due_date > Carbon\Carbon::now()){
						$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
						// $actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="'.route('event.team.registration.edit', $qry->id).'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
						if($qry->source_type == @Config::get('smart.event_registration_source_type')['admin'] && (empty($qry->status) || $qry->status == @Config::get('smart.event_registration_status')['created']))
						{
							$actionHtml .= 	'<a class="btn btn-icon btn-light btn-hover-primary btn-sm delete" href="javascript:;" data-registrationid="'.$qry->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
						}
					//}
					return $actionHtml;
				}

            })->rawColumns(['action', 'status', 'member', 'id_photo', 'event', 'academy_name', 'team_name','entry_name', 'teamathlete_count'])
            ->make(true);

        return $data;
	}

	public function getEventRegStatusCount($eventId)
	{
		$count = "";
		if(@$eventId)
		{
			$qry = EventTeamRegistration::selectRaw('status, count(*) as total')->where('event_id', $eventId)->groupBy('status');
			$count = $qry->get();
		}

		return $count;
	}

	public function getEventFeesByEventId($eventId)
	{
		$fees = "";
		if(@$eventId)
		{
			$qry = EventTeamRegistration::selectRaw('distinct on (uq_team_registration.id) uq_team_registration.id, uq_event_entries_fee.end_date, uq_event_entries_fee.entrance_fee, uq_event_payment.amount')
				->join('uq_event_payment', 'uq_team_registration.id', '=', 'uq_event_payment.registration_id')
				->join('uq_event_entries_fee', 'uq_team_registration.entry_id', '=', 'uq_event_entries_fee.entry_id')
				->where('uq_team_registration.event_id', $eventId)
				->where('uq_event_payment.status', true)
				->where('uq_team_registration.status', @Config::get('smart.event_registration_status')['approved']);
				/*
				->groupBy('uq_event_entries_fee.end_date', 'uq_event_entries_fee.entrance_fee')
				
				*/
			$fees = $qry->get();
		}

		return $fees;
	}

	public function getPaymentByEventId($eventId)
	{
		$fees = "";
		if(@$eventId)
		{
			$qry = EventTeamRegistration::selectRaw('distinct on (uq_team_registration.id) uq_team_registration.id, uq_event_entries_fee.entrance_fee, COALESCE(amount, amount, 0) as amount, round(uq_event_payment.amount*0.99) as fee_amount')
				->leftJoin('uq_event_payment', function($join){
					$join->on('uq_team_registration.id', '=', 'uq_event_payment.registration_id')
						->where('uq_event_payment.status', true);
				})
				->join('uq_event_entries_fee', 'uq_team_registration.entry_id', '=', 'uq_event_entries_fee.entry_id')
				->where('uq_team_registration.event_id', $eventId)
				->where('uq_team_registration.status', @Config::get('smart.event_registration_status')['approved']);
			$fees = $qry->get()->sortBy('amount');
		}

		return $fees;
	}

	public function getRegistrationByStatus($evntId, $status, $searchData)
	{
		$registrations = "";
		if(@$evntId && @$status)
		{
			$qry = EventTeamRegistration::selectRaw('id, status, team_id, academy_id, entry_id, academy_name')
			->where('event_id', $evntId)
			->where('uq_team_registration.status', $status);

			if(@$searchData['search_entry'])
			{
				$qry->where('entry_id', $searchData['search_entry']);
			}

			if(@$searchData['search_academy'])
			{
				$qry->where('academy_id', $searchData['search_academy']);
			}

			if(@$searchData['search_member'])
			{
				$qry->whereHas('member', function($q) use($searchData){
					$q->whereRaw("LOWER(register_number) like ?", array('%'.mb_strtolower($searchData['search_member']).'%'))
					->orWhereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower($searchData['search_member']).'%'))
					->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower($searchData['search_member']).'%'))
					->orWhereRaw("LOWER(contact_phone) like ?", array('%'.mb_strtolower($searchData['search_member']).'%'));
				});
			}

			$registrations = $qry->get();
		}
		
		return $registrations;
	}

	public  function getEventRegByGroup($eventId)
	{
		$regs = "";
		if(@$eventId)
		{
			$qry = EventTeamRegistration::selectRaw('*')
				->where('event_id', $eventId)
				->where('status', @Config::get('smart.event_registration_status')['approved'])
				->orderByRaw('entry_id, entry_belt_id, entry_age_id, entry_weight_id');
			$regs = $qry->get()->load(['entry:id,name,gender_code', 'belt:id,name', 'age:id,start_age,end_age', 'weight:id,weight']);
		}

		return $regs;
	}
	public function getAllEntriesFromEvent($eventId)
	{
		return DB::select("select r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id  
								from uq_comp.uq_team_registration r
								where event_id = ".$eventId."
								group by r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id
								order by r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id");
	}

	public function getAllEntriesFromEventById($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
	{
		return DB::select("select r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id  
								from uq_comp.uq_team_registration r
								where r.event_id = ".$eventId."
								and r.entry_id = ".$entryId." and r.entry_age_id = ".$entryAgeId." 
								and r.entry_belt_id = ".$entryBeltId." and r.entry_weight_id = ".$entryWeightId."
								group by r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id
								order by r.entry_id, r.entry_age_id, r.entry_belt_id, r.entry_weight_id");
	}

	public function getBracketMembersFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId, $isWeightChecked = false)
	{
		//and is_weight_checked = false
		return DB::select("select r.id, r.member_id, r.academy_id, case when a.is_other = 1 then r.academy_name else a.name end as acname 
								from uq_comp.uq_team_registration r
								inner join uq_comp.uq_academy a on r.academy_id = a.id 
								where status = 'approved' and event_id = ".$eventId." 								
								and r.entry_id = ".$entryId." and r.entry_age_id = ".$entryAgeId." 
								and r.entry_belt_id = ".$entryBeltId." and r.entry_weight_id = ".$entryWeightId."
								order by r.academy_id, r.academy_name, r.id");
	}

	public function getBracketGenerationFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
	{
		return DB::select("select ro.id as ro, um.firstname as firstname_one, um.lastname as lastname_one, case when ao.is_other = 1 then ro.academy_name else ao.name end as acname_one, 
								rt.id as rt, umt.firstname as firstname_two, umt.lastname as lastname_two, case when aot.is_other = 1 then rt.academy_name else aot.name end as acname_two,
								rw.id as rw, umw.firstname as firstname_win, umw.lastname as lastname_win, case when aow.is_other = 1 then rw.academy_name else aow.name end as acname_win
								from uq_comp.uq_event_brackets b
								inner join uq_comp.uq_event_entries e on b.entry_id = e.id 
								inner join uq_comp.uq_entry_config_age a on b.entry_age_id  = a.id 
								inner join uq_comp.uq_entry_config_belt be on b.entry_belt_id = be.id 
								inner join uq_comp.uq_entry_config_weight w on b.entry_weight_id = w.id 
								left join uq_comp.uq_team_registration ro on b.reg_one_id = ro.id 
								left join uq_comp.uq_member um on ro.member_id = um.id 
								left join uq_comp.uq_academy ao on ro.academy_id = ao.id 
								left join uq_comp.uq_team_registration rt on b.reg_two_id = rt.id 
								left join uq_comp.uq_member umt on rt.member_id = umt.id 
								left join uq_comp.uq_academy aot on rt.academy_id = aot.id 
								left join uq_comp.uq_team_registration rw on b.reg_winner_id = rw.id 
								left join uq_comp.uq_member umw on rw.member_id = umw.id 
								left join uq_comp.uq_academy aow on rw.academy_id = aow.id 
								where b.event_id = ".$eventId."  and b.entry_id = ".$entryId." and b.entry_age_id = ".$entryAgeId."  
								and b.entry_belt_id = ".$entryBeltId." and b.entry_weight_id = ".$entryWeightId."
								order by b.id");
	}

	public function deleteEventBracket($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
	{
		return EventBrackets::where('event_id', $eventId)->where('entry_id', $entryId)
					->where('entry_age_id', $entryAgeId)->where('entry_belt_id', $entryBeltId)
					->where('entry_weight_id', $entryWeightId)->delete();
	}

	public function createEventBracket($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId, $regOneId, $regTwoId)
	{
		$eventBrackets = new EventBrackets;

		$eventBrackets->event_id = $eventId;
		$eventBrackets->entry_id = $entryId;
		$eventBrackets->entry_age_id = $entryAgeId;
		$eventBrackets->entry_belt_id = $entryBeltId;
		$eventBrackets->entry_weight_id = $entryWeightId;
		$eventBrackets->reg_one_id = $regOneId;
		$eventBrackets->reg_two_id = $regTwoId;
		$winnerId = null;
		if($regOneId != null && $regTwoId == null)
		{
			$winnerId = $regOneId;
		}

		if($regOneId == null && $regTwoId != null)
		{
			$winnerId = $regTwoId;
		}

		$eventBrackets->reg_winner_id = $winnerId;

		return $eventBrackets->save();
	}
}
