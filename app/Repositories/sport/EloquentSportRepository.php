<?php namespace sport;

use sport\Academy;
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

class EloquentSportRepository implements SportRepository {

	public function all()
	{
		return Sport::all();
	}

	public function allPaginate()
	{
		return Sport::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Sport::find($id);
	}

	public function create($input)
	{
		$Sport = new Sport;

		$sport->name = $input['name'];
		$sport->name_en = $input['name_en'];

		$sport->save();
		return $sport;
	}

 	public function update($id, $input)
	{
		$sport = $this->find($id);
		
		$sport->name = $input['name'];
		$sport->name_en = $input['name_en'];

		$sport->save();
		return $sport;
	}

	public function delete($id)
	{
		$sport = $this->find($id);

		$sport->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Sport::select('*')->with('organization:id,name');

        $data = Datatables::make($qry)
            ->filter(function ($qry) use ($searchData) {
                if($searchData->has('name') && $searchData->get('name') !== null)
                {
                    $qry->whereRaw('LOWER(name) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'))
						->orWhereRaw('LOWER(name_en) like ?', array('%'.mb_strtolower($searchData->get('name')).'%'));
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
            ->addColumn('action', function ($sport) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.sport'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="sportEdit('.$sport->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="sportDelete('.$sport->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
				}
				$actionHtml .= '</ul>';
				$actionHtml .= '</div>';
				$actionHtml .= '</div>';

				return $actionHtml;

            })->rawColumns(['organization_id', 'action'])
            ->make(true);

        return $data;
	}

	public function getSportList()
	{
		$sports = Sport::where('is_active', true)
    		->orderBy('sort_order')
    		->get();

		return $sports;
	}
}
