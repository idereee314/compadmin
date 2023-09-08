<?php namespace member;

use member\Member;
use core\sessions\Sessions;
use user\User;
use event\EventRegistration;

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
use Str;

class EloquentMemberRepository implements MemberRepository {

	public function all()
	{
		return Member::all();
	}

	public function allPaginate()
	{
		return Member::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Member::find($id);
	}

	public function create($input)
	{
		$member = new Member;

		$member->user_id = @$input['user_id'];
		$member->register_number = Str::upper(@$input['register_number']);
		$member->firstname = $input['firstname'];
		$member->lastname = $input['lastname'];
		$member->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$member->birth = @$input['birth'];
		$member->status = Config::get('smart.member_status')['created'];
		$member->gender_code = @$input['gender_code'];
		$member->profile_url = @$input['profile_url'];
		$member->id_url = @$input['id_url'];
		$member->country_id = @$input['country_id'];

		$member->save();
		return $member;
	}

 	public function update($id, $input)
	{
		$member = $this->find($id);
		$member->register_number = Str::upper(@$input['register_number']);
		$member->firstname = $input['firstname'];
		$member->lastname = $input['lastname'];
		$member->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$member->birth = @$input['birth'];
		$member->gender_code = @$input['gender_code'];
		$member->status = @$input['status'];
		$member->country_id = @$input['country_id'];
		if(array_key_exists('profile_url', $input))
		{
			$member->profile_url = @$input['profile_url'];
		}

		if(array_key_exists('id_url', $input))
		{
			$member->id_url = @$input['id_url'];
		}

		$member->save();
		return $member;
	}

	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Member::select('*');

        $data = Datatables::make($qry)
			->filter(function ($qry) use ($searchData) {
				if($searchData->has('register_number') && $searchData->get('register_number') !== null)
				{
					$qry->whereRaw("LOWER(register_number) like ?", array('%'.mb_strtolower($searchData->get('register_number')).'%'));
				}

				if($searchData->has('lastname') && $searchData->get('lastname') !== null)
				{
					$qry->whereRaw('LOWER(lastname) like ?', array('%'.mb_strtolower($searchData->get('lastname')).'%'));
				}

				if($searchData->has('firstname') && $searchData->get('firstname') !== null)
				{
					$qry->whereRaw('LOWER(firstname) like ?', array('%'.mb_strtolower($searchData->get('firstname')).'%'));
				}

				if($searchData->has('phone_number') && $searchData->get('phone_number') !== null)
				{
					$qry->whereRaw("LOWER(contact_phone) like ?", array('%'.mb_strtolower($searchData->get('phone_number')).'%'));
				}

				if($searchData->has('gender') && $searchData->get('gender') !== null)
                {
					$qry->where('gender_code', $searchData->get('gender'));				
				}

				if($searchData->has('status') && $searchData->get('status') !== null)
                {
					$qry->where('status', $searchData->get('status'));
				}
				if
				($searchData->has('age') && !empty(array_filter($searchData->get('age'))))
                {
					$qry->whereBetween(DB::raw("date_part('year', AGE(now(), birth))"), $searchData->get('age'));
				}

				if($searchData->has('country') && $searchData->get('country') !== null)
                {
					$qry->where('country_id', $searchData->get('country'));				
				}
			})
			->editColumn('profile_photo', function ($qry) {
				if (@$qry->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($qry->profile_url)) || @env('local'))) {
					return '<a href="javascript:;" class="show-image" data-id="'.$qry->id.'" data-type="profile"><div class="symbol symbol-50 flex-shrink-0"><img class="align-self-end" alt="Profile" src="'.\Storage::disk('s3')->url($qry->profile_url).'" style="max-width: 50px;"></div></a>';
				}
				else 
				{
					return '<div class="symbol symbol-50 flex-shrink-0"><img class="align-self-end" alt="Profile" src="/assets/images/default_profile.jpg" style="max-width: 50px;"></div>';
				}
			})
			->editColumn('id_photo', function ($qry) {
				if ($qry->id_url) {
					return '<a class="btn btn-light show-image" data-id="'.$qry->id.'" data-type="id"><i class="far fa-eye ml-1"></i></a>';
				}
				return "";
			})
			->editColumn('connect_user', function ($qry) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.member'), Config::get('permission.editable'));
				if($permissionEdit)
				{
					return '<button type="button" onclick="connectUser('.$qry->id.')" class="btn btn-outline-secondary">Холбох</button>';
				}
			})
			->editColumn('status', function($qry)
			{
				$status = '<span style="cursor:pointer" class="label label-lg font-weight-bold label-light-'.@Config::get('smart.member_status_class')[$qry->status].' label-inline" onclick="chnageMemberStatus('.$qry->id.')">'.@Config::get('enums.member_status')[$qry->status].'</span>';
				return $status;
			})			
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($member) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.member'), Config::get('permission.editable'));
				$actionHtml = "";
				if($permissionEdit)
				{
					$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon edit" href="javascript:;" data-id="'.$member->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
					if(@$member->status == @Config::get('smart.member_status')['created'])
					{
						$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon delete" href="javascript:;" data-id="'.$member->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
					}
				}

				return $actionHtml;
            })->rawColumns(['profile_photo', 'id_photo', 'connect_user', 'status', 'action'])
            ->make(true);

        return $data;
	}

	public function searchMember($data)
    {
        //DB::enableQueryLog();
        $member = "";
        $qry = Member::selectRaw("id, firstname, lastname, concat(substring(lastname, 1, 1), '.', firstname) as fullname");

        if(!empty(@$data))
		{
			$qry->whereRaw("LOWER(register_number) like ?", array('%'.mb_strtolower(@$data).'%'))
				->orWhereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower(@$data).'%'))
				->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower(@$data).'%'));
        }
        $member = $qry->orderBy('firstname', 'asc')->get();

        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $member;
    }

	public function memberListByEvent($eventId)
	{
        $members = "";
        if(!empty(@$eventId))
		{	
			$qry = Member::selectRaw("uq_member.*")
			->leftJoin('uq_event_registration', 'uq_member.id', '=', 'uq_event_registration.member_id')
			->where('uq_event_registration.event_id', '!=', $eventId);	

			$members = $qry->get();  
        }
		return $members;
	}

	public function getGenderCode()
	{
		$members = "";
		$qry = Member::selectRaw("gender_code, count(*) as total")
			->groupBy('gender_code');	

		$members = $qry->get();  
		return $members;
	}

	public function getMemberToProfileApprovedData($memberId)
	{
		return DB::select("select um.country_id, uc.name as countryname, uer.event_id, re.name as event_name, uer.member_id, ua.name as academy_name, um.lastname, um.firstname, uecb.name as belt, 
		uee.name as entries, uer.status, re.event_date, ueca.start_age , ueca.end_age, uea.place_number, uec.sport_id from uniqdb.uq_comp.uq_event_registration uer 
			left join uniqdb.rt_listing.rti_event re on re.id = uer.event_id
			left join uniqdb.uq_comp.uq_member um on um.id = uer.member_id 
			left join uniqdb.uq_comp.uq_country uc on uc.id = um.country_id
			left join uniqdb.uq_comp.uq_entry_config_belt uecb on uecb.id = uer.entry_belt_id 
			left join uniqdb.uq_comp.uq_entry_config_age ueca on ueca.id = uer.entry_age_id 
			left join uniqdb.uq_comp.uq_event_entries uee on uee.id = uer.entry_id
			left join uniqdb.uq_comp.uq_entry_config_weight uecw on uecw.id = uer.entry_weight_id 
			left join uniqdb.uq_comp.uq_event_award uea on uea.event_registration_id = uer.id
			left join uniqdb.uq_comp.uq_academy ua on  ua.id = uer.academy_id 
			left join uniqdb.uq_comp.uq_event_config uec on uec.event_id = uer.event_id 
			where um.id = $memberId and uer.status = 'approved' and uer.is_weight_checked = TRUE and uec.is_active = TRUE
			group by uer.event_id, re.name, um.firstname, uer.member_id, um.lastname, uecb.name, uee.name, uer.status, 
			ua.name, re.event_date, ueca.start_age , ueca.end_age, uea.place_number, uec.sport_id, um.country_id, uc.name
			order by re.event_date desc
			");
	}
	
	public function getMemberToProfileAllData($memberId)
	{
		return DB::select("select um.country_id, uc.name as countryname, uer.event_id, re.name as event_name, uer.member_id, ua.name as academy_name, um.lastname, um.firstname, uecb.name as belt, 
		uee.name as entries, uer.status, re.event_date, ueca.start_age , ueca.end_age, uea.place_number, uec.sport_id from uniqdb.uq_comp.uq_event_registration uer 
			left join uniqdb.rt_listing.rti_event re on re.id = uer.event_id
			left join uniqdb.uq_comp.uq_member um on um.id = uer.member_id 
			left join uniqdb.uq_comp.uq_country uc on uc.id = um.country_id
			left join uniqdb.uq_comp.uq_entry_config_belt uecb on uecb.id = uer.entry_belt_id 
			left join uniqdb.uq_comp.uq_entry_config_age ueca on ueca.id = uer.entry_age_id 
			left join uniqdb.uq_comp.uq_event_entries uee on uee.id = uer.entry_id
			left join uniqdb.uq_comp.uq_entry_config_weight uecw on uecw.id = uer.entry_weight_id 
			left join uniqdb.uq_comp.uq_event_award uea on uea.event_registration_id = uer.id
			left join uniqdb.uq_comp.uq_academy ua on  ua.id = uer.academy_id 
			left join uniqdb.uq_comp.uq_event_config uec on uec.event_id = uer.event_id 
			where um.id = $memberId and uec.is_active = TRUE
			group by uer.event_id, re.name, um.firstname, uer.member_id, um.lastname, uecb.name, uee.name, uer.status, ua.name, re.event_date, 
			ueca.start_age , ueca.end_age, uea.place_number, uec.sport_id, um.country_id, uc.name
			order by re.event_date desc
			");
	}

	public function getUpcomingJiuJitsuEvent()
	{
		return DB::select("select uec.event_id, uec.is_active , uec.sport_id , uec.is_team , uec.reg_start_date , uec.reg_end_date , re.name as event_name , 
		re.event_date , re.description as event_description , rel.object_location_id ,rol.object_name, rep.picture_type_id, rep.url, rel.object_location_id, 
		ro.name as org_name from uniqdb.uq_comp.uq_event_config uec 
			left join uniqdb.rt_listing.rti_event re on re.id = uec.event_id 
			left join uniqdb.rt_listing.rti_event_location rel on rel.event_id = uec.event_id 
			left join uniqdb.rt_listing.rti_object_location rol on rol.id = rel.object_location_id 
			left join uniqdb.rt_listing.rti_event_picture rep on rep.event_id = re.id 
			left join uniqdb.rt_listing.rti_organization_event roe on roe.event_id = uec.event_id 
			left join uniqdb.rt_listing.rti_organization ro on ro.id = roe.organization_id 
			where uec.sport_id = 1 and re.event_date > now() and rep.picture_type_id = 15
			group by uec.event_id, uec.is_active , uec.sport_id , uec.is_team , uec.reg_start_date , uec.reg_end_date , re.name , 
			re.event_date , re.description , rel.object_location_id ,rol.object_name,rep.picture_type_id, rep.url, rel.object_location_id, ro.name
			order by re.event_date asc");
	}

	public function getPastJiuJitsuEvent()
	{
		return DB::select("select uec.event_id, uec.is_active , uec.sport_id , uec.is_team , uec.reg_start_date , uec.reg_end_date , re.name as event_name , 
		re.event_date , re.description as event_description , rel.object_location_id ,rol.object_name, ro.name as org_name from uniqdb.uq_comp.uq_event_config uec 
			left join uniqdb.rt_listing.rti_event re on re.id = uec.event_id 
			left join uniqdb.rt_listing.rti_event_location rel on rel.event_id = uec.event_id 
			left join uniqdb.rt_listing.rti_object_location rol on rol.id = rel.object_location_id 
			left join uniqdb.rt_listing.rti_organization_event roe on roe.event_id = uec.event_id 
			left join uniqdb.rt_listing.rti_organization ro on ro.id = roe.organization_id 
			where uec.sport_id = 1 and re.event_date < now() 
			group by uec.event_id, uec.is_active , uec.sport_id , uec.is_team , uec.reg_start_date , uec.reg_end_date , re.name , re.event_date , re.description , rel.object_location_id ,rol.object_name, ro.name
			order by re.event_date desc
			");
	}
	
	public function getAthleteAcademyInfo($memberId)
	{
		return DB::select("select ua.name, uer.academy_name from uniqdb.uq_comp.uq_event_registration uer 
				join uniqdb.uq_comp.uq_member um on um.id = uer.member_id 
				join uniqdb.uq_comp.uq_academy ua on ua.id = uer.academy_id 
				where um.id = $memberId and ua.type = 'academy'
				group by ua.name, uer.academy_name");
	}

	public function getAthleteSchoolInfo($memberId)
	{
		return DB::select("select ua.name , uer.academy_name from uniqdb.uq_comp.uq_event_registration uer 
				join uniqdb.uq_comp.uq_member um on um.id = uer.member_id 
				join uniqdb.uq_comp.uq_academy ua on ua.id = uer.academy_id 
				where um.id = $memberId and ua.type = 'highschool'
				group by ua.name, uer.academy_name");
	}
	
	public function getAthleteUniversityInfo($memberId)
	{
		return DB::select("select ua.name, uer.academy_name from uniqdb.uq_comp.uq_event_registration uer 
				join uniqdb.uq_comp.uq_member um on um.id = uer.member_id 
				join uniqdb.uq_comp.uq_academy ua on ua.id = uer.academy_id 
				where um.id = $memberId and ua.type = 'university'
				group by ua.name, uer.academy_name");
	}

}
