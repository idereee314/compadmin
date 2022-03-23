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
		$member->register_number = $input['register_number'];
		$member->firstname = $input['firstname'];
		$member->lastname = $input['lastname'];
		$member->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$member->birth = @$input['birth'];
		$member->status = Config::get('smart.member_status')['created'];
		$member->gender_code = @$input['gender_code'];

		$member->save();

		return $member;
	}

 	public function update($id, $input)
	{
		$member = $this->find($id);
		$member->user_id = @$input['user_id'];
		$member->register_number = $input['register_number'];
		$member->firstname = $input['firstname'];
		$member->lastname = $input['lastname'];
		$member->contact_phone = preg_replace('/\s+/', '', @$input['contact_phone']);
		$member->birth = @$input['birth'];
		$member->gender_code = @$input['gender_code'];

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
					$qry->where('register_number', $searchData->get('register_number'));
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
					$qry->where('phone_number', $searchData->get('phone_number'));
				}
			})
			->editColumn('profile_photo', function ($qry) {
				if ($qry->profile_url) {
					return '<a href="javascript:;" class="show-image" data-id="'.$qry->id.'" data-type="profile"><img class="h-75 align-self-end" alt="Profile" src="'.@Config::get('smart.cloud_image_url').$qry->profile_url.'" style="max-width: 50px;"></a>';
				}
				return "";
			})
			->editColumn('id_photo', function ($qry) {
				if ($qry->id_photo) {
					return '<a class="btn btn-light show-image" data-id="'.$qry->id.'" data-type="id"><i class="far fa-eye ml-1"></i></a>';
				}
				return "";
			})
			->editColumn('connect_user', function ($qry) {
					return '<button type="button" onclick="connectUser('.$qry->id.')" class="btn btn-outline-secondary">Холбох</button>';
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

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link edit" href="javascript:;" data-id="'.$member->id.'"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="javascript:;" onclick="memberDelete('.$member->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

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
}
