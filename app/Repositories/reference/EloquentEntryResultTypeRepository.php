<?php namespace reference;

use reference\EntryResultType;
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

class EloquentEntryResultTypeRepository implements EntryResultTypeRepository {

	public function all()
	{
		return EntryResultType::all();
	}

	public function allPaginate()
	{
		return EntryResultType::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EntryResultType::find($id);
	}

	public function create($input)
	{
		$EntryResultType = new EntryConfigBelt;

		$EntryResultType->save();
		return $EntryResultType;
	}

 	public function update($id, $input)
	{
		$EntryResultType = $this->find($id);

		$EntryResultType->save();

		return $EntryResultType;
	}

	public function delete($id)
	{
		$EntryResultType = $this->find($id);

		$EntryResultType->delete();
	}

}
