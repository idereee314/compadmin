<?php namespace attribute;
 
use attribute\Attribute as Attribute;

use Auth;
use App;
use ConfigHelper;
use Yajra\DataTables\Facades\DataTables;
use SecurityHelper;
use Config;
use DB;
 
class EloquentAttributeRepository implements AttributeRepository {
 
	public function all()
	{
		return Attribute::all();
	}

	public function find($id)
	{
		return Attribute::find($id);
	}

	public function create($input)
	{
		$item = new Attribute;

		$item->save();

		return $item;
	}

	public function update($id, $input)
	{
		$item = $this->find($id);
		
		$item->save();
		
		$item->attributeGroups()->sync(@$input['groups']);

		return $item;
	}

	public function delete($id)
	{
		$item = Attribute::onlyTrashed()->find($id);
		$item->forceDelete();
	}

	public function findWithTrashed($id)
	{
		return Attribute::withTrashed()
                ->where('id', $id)
                ->first();
	}

	public function softDelete($id)
    {
        $item = Attribute::find($id);
        $item->delete();
    }

    public function attributeRecovery($id)
    {
        $item= Attribute::withTrashed()->Find($id);
        $item->restore();
    }

	public function getDatatableList($searchData)
    {   
		$qry = Attribute::select('*')->withTrashed()->with('attributeCategory:id,name', 'attributeLovvalues:id,name,attribute_id', 'attributeGroups:id,name');
        
        $data = Datatables::make($qry)
		->setRowClass(function ($qry) {
			return $qry->deleted_at != null ? 'alert-danger' : '';
		})
		->filter(function ($qry) use ($searchData) {
			if($searchData->has('code') && $searchData->get('code') !== null)    
			{
				$qry->whereRaw('LOWER(code) like ?', array('%'.mb_strtolower($searchData->get('code')).'%'));
			}
			if($searchData->has('name') && $searchData->get('name') !== null)    
			{
                $qry->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
            }
			if($searchData->has('objects') && $searchData->get('objects') !== null)    
			{	
                $qry->whereRaw("'".Config::get('smart.attribute_objects')[$searchData->get('objects')]."' = ANY(ref_attribute.objects)");
            }
			if($searchData->has('data_type') && $searchData->get('data_type') !== null)
            {
                $qry->where('data_type', $searchData->get('data_type'));
            }
			if($searchData->has('attribute_category_id') && $searchData->get('attribute_category_id') !== null)
            {
                $qry->where('attribute_category_id', $searchData->get('attribute_category_id'));
            }
			if($searchData->has('request_type') && $searchData->get('request_type') !== null)
            {
				$qry->whereHas('requestTypes', function($q) use ($searchData){
					$q->where('ref_request_type.id', $searchData->get('request_type'));
				});
            }
			if($searchData->has('groups') && $searchData->get('groups') !== null)
            {
				$qry->whereHas('attributeGroups', function($q) use ($searchData){
					$q->where('ref_attribute_group.id', $searchData->get('groups'));
				});
            }
			if($searchData->has('is_service') && $searchData->get('is_service') !== null)
            {
                $qry->where('is_service', $searchData->get('is_service'));
            }
			
		})
		
		->editColumn('created_at', function($item)
		{
			return $item->created_at;
		})
        ->addColumn('action', function ($item) {
			$permissionEdit = SecurityHelper::checkPermission(Config::get('permission.attribute_entry'), Config::get('permission.generic_change'));
			$actionHtml = "";

			if($permissionEdit)
			{	
				if(empty(@$item->deleted_at)){
					$actionHtml .= '<div class="dropdown dropdown-inline">';
					
					$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true"><i class="la la-cog"></i></a>';
						$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="display: none;">';
							$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
								$actionHtml .= '<li class="nav-item"><a class="nav-link edit" href="javascript:;" data-attributeid="'.$item->id.'"><i class="nav-icon la la-edit"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
								$actionHtml .= '<li class="nav-item"><a class="nav-link cancel" href="javascript:;" data-attributeid="'.$item->id.'"><i class="nav-icon flaticon-cancel"></i><span class="nav-text">'.trans('display.general_inactive').'</span></a></li>';
								$actionHtml .= '<li class="nav-item"><a class="nav-link change" href="javascript:;" data-attributeid="'.$item->id.'"><i class="nav-icon flaticon-interface-4"></i><span class="nav-text">Солих</span></a></li>';
								
								if($item->data_type == @Config::get('smart.attribute_data_type')['lov']) {
									$actionHtml .= '<div class="separator separator-dashed mt-2 mb-2"></div>';
									$actionHtml .= '<li class="nav-item"><a class="nav-link lovvalue-add" href="javascript:;" data-attributeid="'.$item->id.'"><i class="nav-icon flaticon-add"></i><span class="nav-text">'.trans('display.attribute_lovvalue_add').'</span></a></li>';
								}
								if($item->is_service){
									$actionHtml .= '<div class="separator separator-dashed mt-2 mb-2"></div>';
									$actionHtml .= '<li class="nav-item"><a class="nav-link attributeapi-add" href="javascript:;" data-attributeid="'.$item->id.'"><i class="nav-icon flaticon-add"></i><span class="nav-text">'.trans('display.attribute_api_add').'</span></a></li>';
								}
							$actionHtml .= '</ul>';
						$actionHtml .= '</div>';
					$actionHtml .= '</div>';
				}
				else
				{
					$actionHtml .=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon recovery" title="Сэргээх" data-attributeid="'.$item->id.'"><i class="nav-icon flaticon2-reload"></i></a>';
					$actionHtml .=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Устгах" data-attributeid="'.$item->id.'"><i class="la la-trash"></i></a>';
				}
			}

			return $actionHtml;
        })->rawColumns(['action'])
        
        ->make(true);
        return $data;
	}

	public function findByIds($ids)
	{
		$attributes = "";
		if(@$ids)
		{
			$qry = Attribute::selectRaw("*")
				// ->where('ref_attribute.is_active', true)
				->whereIn('id', $ids);
			$attributes = $qry->get();
		}

		return $attributes;
	}

}