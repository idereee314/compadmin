<?php namespace organization;

use organization\Organization;
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

class EloquentOrganizationRepository implements OrganizationRepository {

	public function all()
	{
		return Organization::all();
	}

	public function allPaginate()
	{
		return Organization::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Organization::find($id);
	}

	public function findOrganizationByName($orgName)
    {
        //DB::enableQueryLog();
        $organization = "";

        if(!empty(@$orgName))
		{
			$qry = Organization::select('*');
			$qry->whereRaw("LOWER(name) like ?", array('%'.mb_strtolower(@$orgName).'%'));
			$organization = $qry->orderBy('name', 'asc')->get();
        }
        /*
        $queries = DB::getQueryLog();
        dd($queries);
        */
		return $organization;
    }

}
