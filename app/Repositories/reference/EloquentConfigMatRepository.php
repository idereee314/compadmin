<?php namespace reference;

use reference\ConfigMat;
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

class EloquentConfigMatRepository implements ConfigMatRepository {

	public function all()
	{
		return ConfigMat::all();
	}

	public function allPaginate()
	{
		return ConfigMat::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return ConfigMat::find($id);
	}

	public function create($input)
	{
		$configMat = new ConfigMat;
		
		$configMat->name = $input['name'];
		$configMat->name_en = $input['name_en'];
		$configMat->prefix = $input['prefix'];
		$configMat->event_id = $input['event_id'];

		$configMat->save();

		return $configMat;
	}

 	public function update($id, $input)
	{
		$configMat = $this->find($id);
		$configMat->name = $input['name'];
		$configMat->name_en = $input['name_en'];
		$configMat->prefix = $input['prefix'];
		$configMat->event_id = $input['event_id'];

		$configMat->save();

		return $configMat;
	}

	public function delete($id)
	{
		$ConfigMat = $this->find($id);

		$ConfigMat->delete();
	}

}
