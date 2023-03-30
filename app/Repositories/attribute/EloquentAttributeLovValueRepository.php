<?php namespace attribute;
 
use attribute\AttributeLovValue as AttributeLovValue;

use Auth;
use App;
use ConfigHelper;
use Yajra\DataTables\Facades\DataTables;
use SecurityHelper;
use Config;
 
class EloquentAttributeLovValueRepository implements AttributeLovValueRepository {
 
	public function all()
	{
		return AttributeLovValue::all();
	}

	public function find($id)
	{
		return AttributeLovValue::find($id);
	}

	public function create($input)
	{
		$item = new AttributeLovValue;
		$item->attribute_id = $input['attribute_id'];
		$item->code = $input['code'];
		$item->name = $input['name'];
		// $item->is_active = @$input['is_active'] ? $input['is_active'] : false;

		$item->save();
	}

	public function update($id, $input)
	{
		$item = $this->find($id);
		$item->attribute_id = $input['attribute_id'];
		$item->code = $input['code'];
		$item->name = $input['name'];
		// $item->is_active = @$input['is_active'] ? $input['is_active'] : false;

		$item->save();
	}

	public function delete($id)
	{
		$item = AttributeLovValue::onlyTrashed()->find($id);
		$item->forceDelete();
	}

	public function softDelete($id)
    {
        $item = AttributeLovValue::find($id);
        $item->delete();
    }

    public function attrLovvalueRecovery($id)
    {
        $item= AttributeLovValue::withTrashed()->Find($id);
        $item->restore();
    }

}