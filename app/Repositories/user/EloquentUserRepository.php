<?php namespace user;

use user\User;
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

class EloquentUserRepository implements UserRepository {

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

	public function searchUser($data)
    {
        //DB::enableQueryLog();
        $user = "";
        $qry = User::selectRaw("*, concat(substring(lastname, 1, 1), '.', firstname) as fullname");

        if(!empty(@$data))
		{
			$qry->whereRaw("LOWER(firstname) like ?", array('%'.mb_strtolower(@$data).'%'))
				->orWhere('mobile_number', @$data)
				->orWhereRaw("LOWER(lastname) like ?", array('%'.mb_strtolower(@$data).'%'));
        }
        $user = $qry->orderBy('firstname', 'asc')->get();
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $user;
    }
}
