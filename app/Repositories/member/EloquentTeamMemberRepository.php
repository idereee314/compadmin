<?php namespace member;

use member\TeamMember;
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

class EloquentTeamMemberRepository implements TeamMemberRepository {

	public function all()
	{
		return TeamMember::all();
	}

	public function allPaginate()
	{
		return TeamMember::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return TeamMember::find($id);
	}

	public function create($input)
	{
		$teamMember = new TeamMember;

		$teamMember->user_id = @$input['user_id'];
		$teamMember->register_number = $input['register_number'];
		$teamMember->firstname = $input['firstname'];
		$teamMember->lastname = $input['lastname'];
		$teamMember->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$teamMember->birth = @$input['birth'];
		$teamMember->status = Config::get('smart.member_status')['created'];
		$teamMember->gender_code = @$input['gender_code'];
		$teamMember->profile_url = @$input['profile_url'];
		$teamMember->id_url = @$input['id_url'];

		$teamMember->save();
		return $teamMember;
	}

 	public function update($id, $input)
	{
		$teamMember = $this->find($id);
		$teamMember->register_number = Str::upper($input['register_number']);
		$teamMember->firstname = $input['firstname'];
		$teamMember->lastname = $input['lastname'];
		$teamMember->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$teamMember->birth = @$input['birth'];
		$teamMember->gender_code = @$input['gender_code'];
		$teamMember->status = @$input['status'];
		if(array_key_exists('profile_url', $input))
		{
			$teamMember->profile_url = @$input['profile_url'];
		}

		if(array_key_exists('id_url', $input))
		{
			$teamMember->id_url = @$input['id_url'];
		}

		$teamMember->save();
		return $teamMember;
	}

	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = TeamMember::select('*');

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
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.teamMember'), Config::get('permission.editable'));
				if($permissionEdit)
				{
					return '<button type="button" onclick="connectUser('.$qry->id.')" class="btn btn-outline-secondary">Холбох</button>';
				}
			})
			->editColumn('status', function($qry)
			{
				$status = '<span style="cursor:pointer" class="label label-lg font-weight-bold label-light-'.@Config::get('smart.teamMember_status_class')[$qry->status].' label-inline" onclick="chnageTeamMemberStatus('.$qry->id.')">'.@Config::get('enums.teamMember_status')[$qry->status].'</span>';
				return $status;
			})			
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($teamMember) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.teamMember'), Config::get('permission.editable'));
				$actionHtml = "";
				if($permissionEdit)
				{
					$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon edit" href="javascript:;" data-id="'.$teamMember->id.'" title="'.trans('display.general_edit').'"><i class="la la-edit"></i></a>';
					if(@$teamMember->status == @Config::get('smart.teamMember_status')['created'])
					{
						$actionHtml .= 	'<a class="btn btn-sm btn-clean btn-icon delete" href="javascript:;" data-id="'.$teamMember->id.'" title="'.trans('display.general_delete').'"><i class="la la-trash"></i></li>';
					}
				}

				return $actionHtml;
            })->rawColumns(['profile_photo', 'id_photo', 'connect_user', 'status', 'action'])
            ->make(true);

        return $data;
	}

}
