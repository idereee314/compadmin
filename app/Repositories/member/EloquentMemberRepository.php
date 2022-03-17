<?php namespace member;

use member\Member;
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

		// dd($input);

		$member->user_id = @$input['user_id'];
		$member->register_number = $input['register_number'];
		$member->firstname = $input['firstname'];
		$member->lastname = $input['lastname'];
		$member->contact_phone = @$input['contact_phone'];
		$member->birth = @$input['birth'];
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
		$member->contact_phone = @$input['contact_phone'];
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
		//$qry = Member::select('*')->with('user:id,firstname');
		$qry = Member::select('*');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('username') && $searchData->get('username') !== null)
                {
                    $qry->whereRaw('LOWER(sd_user.username) like ?', array('%'.mb_strtolower($searchData->get('username')).'%'));
                }

                if($searchData->has('firstname') && $searchData->get('firstname') !== null)
                {
                    $qry->whereRaw('LOWER(firstname) like ?', array('%'.mb_strtolower($searchData->get('firstname')).'%'));
				}

                if($searchData->has('user_mail') && $searchData->get('user_mail') !== null)
                {
                    $qry->whereRaw('LOWER(sd_user.email) like ?', array('%'.mb_strtolower($searchData->get('user_mail')).'%'));
                }
            })
			->editColumn('profile_photo', function ($qry) {
				if ($qry->profile_photo) {
					return '<button class="btn btn-light" onclick="showImage('.$qry->id.')"><i class="far fa-eye ml-1"></i></button>';
				}
				return "";
			})
			->editColumn('id_photo', function ($qry) {
				if ($qry->id_photo) {
					return '<button class="btn btn-light" onclick="showImage('.$qry->id.')"><i class="far fa-eye ml-1"></i></button>';
				}
				return "";
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
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="memberEdit('.$member->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
				$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="memberDelete('.$member->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['profile_photo', 'id_photo', 'action'])
            ->make(true);

        return $data;
	}
}
