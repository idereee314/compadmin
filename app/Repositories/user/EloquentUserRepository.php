<?php namespace user;

use user\CompadUser;
use core\sessions\Sessions;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use SecurityHelper;
use Carbon;
use Session;
use Config;

class EloquentUserRepository implements UserRepository {

	public function all()
	{
		return CompadUser::all();
	}

	public function allPaginate()
	{
		return CompadUser::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return CompadUser::find($id);
	}


	public function create($input)
	{
		$user = new CompadUser;

		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->email = $input['email'];
		$user->username = $input['username'];
		$user->password = md5($input['password']);

		$user->save();
	}

	public function updateUserPassword($id, $input)
	{
		$user = $this->find($id);

		$user->password = md5($input['password']);

		$user->save();
	}

 	public function findByEmail($email)
 	{
 		$user = CompadUser::where('email', '=', $email)->first();
 		return $user;
	}

	public function findByUsername($username)
 	{
 		$user = CompadUser::where('username', '=', $username)->first();
 		return $user;
 	}

	public function findByUsernamePassword($username, $password)
	{
		$user = CompadUser::where('password', md5($password))->where('username', $username)->first();

		return $user;
	}

	public function findByUserIdPassword($userId, $password)
	{
		$query = CompadUser::where('password', md5($password))->where('user_id', $userId);

		$user = $query->first();

		return $user;
	}

	public function getAllActiveUsers()
 	{
		$query = CompadUser::where('user_id', '<>', Auth::user()->user_id);
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->orderBy('last_active', 'desc')->get();
	}

	public function getNonAllActiveUsers($users)
 	{
		$query = CompadUser::whereNotIn('user_id', $users);
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->orderBy('last_active', 'desc')->get();
	}

 	public function getOnlineUsers()
 	{
		$time =  time() - (config('session.lifetime')*60);

		$sessionQuery = Sessions::select('user_id')->where('last_activity','>=', $time);

		if(Auth::user())
		{
			$sessionQuery->where('user_id', '<>', Auth::user()->user_id);
		}

		$totalActiveUsers = $sessionQuery->groupBy('user_id')->get()->pluck('user_id')->toArray();

		$query = CompadUser::whereIn('user_id', $totalActiveUsers);
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->orderBy('firstname', 'asc')->get();
	}

	public function getOnlineUsersByRedis($onlineUsers)
 	{
		$query = CompadUser::whereIn('user_id', $onlineUsers);
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->orderBy('firstname', 'asc')->get();
	}

	public function getOnlineUsersCount()
 	{
		$time =  time() - (config('session.lifetime')*60);

		$totalActiveUsers = Sessions::where('last_activity','>=', $time)->count(DB::raw('DISTINCT user_id'));

		return $totalActiveUsers;
 	}

 	public function getOfflineUsers($onlineUsers)
 	{
		$query = CompadUser::whereNotIn('user_id', $onlineUsers)->orderBy('last_active', 'desc');
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->orderBy('last_active', 'desc')->take(10)->get();
	}

	public function getOfflineUsersCount($onlineUsers)
 	{
		$query = CompadUser::whereNotIn('user_id', $onlineUsers);
		$query->whereHas('setRole', function($q) {
			$q->where('is_active', 'TRUE');
		});

		return $query->count();
 	}

 	public function update($id, $input)
	{
		$user = $this->find($id);
		$user->firstname = $input['firstname'];
		$user->lastname = $input['lastname'];
		$user->username = $input['username'];
		$user->email = $input['email'];

		$user->save();
	}

	public function updateLastActive($user)
	{
		if($user != null)
		{
			$user->last_active = DateHelper::getCurrentDate('Y-m-d H:i:s');
			$user->save();
		}
	}

	public function delete($id)
	{
		$user = $this->find($id);

		$user->delete();
	}

    public function getDatatableList($searchData)
    {
		$user = CompadUser::select('*');

        $qry = $user;
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
			->editColumn('active', function ($user) {
                return @Config::get('enums.is_boolean')[$user->is_active];
			})
            ->addColumn('action', function ($user) {
				// $permissionEdit = SecurityHelper::checkPermission(Config::get('permission.user_management_user'), Config::get('permission.generic_edit'));
				// $permissionDelete = SecurityHelper::checkPermission(Config::get('permission.user_management_user'), Config::get('permission.generic_delete'));
				// $permissionChangePassword = SecurityHelper::checkPermission(Config::get('permission.user_management_user'), Config::get('permission.user_password_change'));

				$actionHtml = '<div class="btn-group dropup">';
					$actionHtml .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">';
						$actionHtml .= '<i class="fa fa-server"></i>';
						$actionHtml .= '<span class="sr-only">Toggle Dropdown</span>';
					$actionHtml .= '</button>';
					$actionHtml .= '<ul class="dropdown-menu float-right">';
					// if($permissionEdit)
					// {
						$actionHtml .= '<a href="javascript:;" class="dropdown-item user-edit" data-userid="'.$user->user_id.'">'.trans('display.general_edit').'</a>';
					// }
					// if($permissionDelete && count($user->roles) == 0)
					// {
					$actionHtml .= '<a href="javascript:;" class="dropdown-item user-delete" data-userid="'.$user->user_id.'">'.trans('display.general_delete').'</a>';
					// }
					$actionHtml .= '<li class="divider"></li>';
					// if($permissionChangePassword)
					// {
						$actionHtml .= '<a href="javascript:;" class="dropdown-item change-password" data-userid="'.$user->user_id.'">'.trans('display.user_password_change').'</a>';
					// }
					$actionHtml .= '</ul>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['action','role'])
            ->make(true);

        return $data;
	}
}
