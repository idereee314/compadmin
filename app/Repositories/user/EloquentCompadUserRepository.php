<?php namespace user;

use user\CompadUser as User;
use core\sessions\Sessions;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

use \Auth as Auth;
use SecurityHelper;
use Carbon;
use Session;
use Config;

class EloquentCompadUserRepository implements CompadUserRepository {

	public function all()
	{
		return User::all();
	}

	public function allPaginate()
	{
		return User::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return User::find($id);
	}

	public function create($input)
	{
		$user = new User;

		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->email = $input['email'];
		$user->username = $input['username'];
		$user->phone_number = @$input['phone_number'];
		$user->password = md5($input['password']);

		$user->save();
	}

 	public function findByEmail($email)
 	{
 		$user = User::where('email', '=', $email)->first();
 		return $user;
	}

 	public function update($id, $input)
	{
		$user = $this->find($id);
		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->username = $input['username'];
		$user->phone_number = @$input['phone_number'];
		$user->email = $input['email'];

		$user->save();
	}

	public function findByUsernamePassword($username, $password)
    {
        $user = User::where('password', md5($password))->where('username', $username)->first();
        return $user;
    }
	
	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

	public function findByUserIdPassword($userId, $password)
    {
        $query = User::where('password', md5($password))->where('id', $userId);

        if ($query->count() == 1) {
          return true;
        }

        return false;
    }

    public function updateUserPassword($id, $input)
    {
        $user = User::find($id);

        $user->password = md5($input['password']);

        $user->save();
    }

    public function getDatatableList($searchData)
    {
		$qry = User::select('*')->with('roles');

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
			->editColumn('role', function ($user)
			{
				if(count($user->roles) > 0)
				{
					$roles = $user->roles->implode('name', ', ');
				}

				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.user'), Config::get('permission.editable'));
				if($permissionEdit)
				{
					$html = '<div>'.@$roles.'&nbsp;<i onclick="editRole('.$user->id.')" class="far fa-edit" style="cursor:pointer"></i></div>';	
				}
				else
				{
					$html = '<div>'.@$roles.'&nbsp;</div>';	
				}

				return $html;
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($compaduser) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.user'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';

				// if($compaduser->username != 'superadmin')
				// {
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="compadUserEdit('.$compaduser->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .=  '<li class="nav-item"><a class="nav-link" href="#" onclick="changePassword('.$compaduser->id.')"><i class="nav-icon fas fa-exchange-alt"></i><span class="nav-text">'.trans('display.user_password_change').'</span></a></li>';
					if(Auth::user()->id != $compaduser->id)
					{
						$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="compadUserDelete('.$compaduser->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
					}
				}
				//}
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action', 'role'])
            ->make(true);

        return $data;
	}

	public function searchUser($data)
    {
        //DB::enableQueryLog();
        $user = "";
        $qry = User::selectRaw("*, concat(substring(lastname, 1, 1), '.', firstname) as fullname");

        if(!empty(@$data))
		{
			$qry->whereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower(@$data).'%'))
				->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower(@$data).'%'))
				->orWhereRaw("LOWER(username) like ?", array('%'.mb_strtolower(@$data).'%'));
				// ->where("phone_number", @$data);
        }
        $user = $qry->orderBy('firstname', 'asc')->get();
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $user;
    }
}
