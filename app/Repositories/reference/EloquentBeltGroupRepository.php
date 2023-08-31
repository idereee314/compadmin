<?php namespace reference;

use reference\BeltGroup;
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

class EloquentBeltGroupRepository implements BeltGroupRepository {

	public function all()
	{
		return BeltGroup::all();
	}

	public function allPaginate()
	{
		return BeltGroup::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return BeltGroup::find($id);
	}

	public function create($input)
	{
		$BeltGroup = new EntryConfigBelt;

		$BeltGroup->save();
		return $BeltGroup;
	}

 	public function update($id, $input)
	{
		$BeltGroup = $this->find($id);

		$BeltGroup->save();

		return $BeltGroup;
	}

	public function delete($id)
	{
		$BeltGroup = $this->find($id);

		$BeltGroup->delete();
	}

}
