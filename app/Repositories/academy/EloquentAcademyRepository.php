<?php namespace academy;

use academy\Academy;
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

class EloquentAcademyRepository implements AcademyRepository {

	public function all()
	{
		return Academy::all();
	}

	public function allPaginate()
	{
		return Academy::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Academy::find($id);
	}

	public function create($input)
	{
		$academy = new Academy;

		$academy->organization_id = @$input['organization_id'];
		$academy->name = $input['name'];
		$academy->name_en = $input['name_en'];
		$academy->sort_order = $input['sort_order'];
		$academy->type = $input['type'];

		$academy->save();
		return $academy;
	}

 	public function update($id, $input)
	{
		$academy = $this->find($id);
		if(array_key_exists('new_organization_id', $input))
		{
			$academy->organization_id = @$input['organization_id'];
		}
		$academy->name = $input['name'];
		$academy->name_en = $input['name_en'];
		$academy->sort_order = $input['sort_order'];
		$academy->type = $input['type'];

		$academy->save();
		return $academy;
	}

	public function delete($id)
	{
		$academy = $this->find($id);

		$academy->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Academy::select('*')->with('organization:id,name');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('register_number_search') && $searchData->get('register_number_search') !== null)
                {
                    $qry->whereRaw('LOWER(register_number) like ?', array('%'.mb_strtolower($searchData->get('register_number_search')).'%'));
                }

                if($searchData->has('firstname') && $searchData->get('firstname') !== null)
                {
                    $qry->whereRaw('LOWER(firstname) like ?', array('%'.mb_strtolower($searchData->get('firstname')).'%'));
				}

                if($searchData->has('lastname') && $searchData->get('lastname') !== null)
                {
                    $qry->whereRaw('LOWER(lastname) like ?', array('%'.mb_strtolower($searchData->get('lastname')).'%'));
                }

				if($searchData->has('phone_number') && $searchData->get('phone_number') !== null)
                {
                    $qry->whereRaw('LOWER(phone_number) like ?', array('%'.mb_strtolower($searchData->get('phone_number')).'%'));
                }
            })
			->editColumn('type', function($qry)
			{
				return @Config::get('enums.org_type')[$qry->type];
			})
			->editColumn('created_at', function($qry)
			{
				return $qry->created_at;
			})
            ->addColumn('action', function ($academy) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.academy'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="academyEdit('.$academy->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="academyDelete('.$academy->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				}
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['organization_id', 'action'])
            ->make(true);

        return $data;
	}
}
