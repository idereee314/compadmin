<?php namespace user;

use user\CompadRole;
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

class EloquentCompadRoleRepository implements CompadRoleRepository {

	public function all()
	{
		return CompadRole::all();
	}

	public function allPaginate()
	{
		return CompadRole::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return CompadRole::find($id);
	}
}
