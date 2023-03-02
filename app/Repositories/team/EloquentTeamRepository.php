<?php namespace team;

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

class EloquentTeamRepository implements TeamRepository {

	public function all()
	{
		return Team::all();
	}

	public function allPaginate()
	{
		return Team::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return Team::find($id);
	}

	public function create($input)
	{
		$Team = new Team;

		$team->name = $input['name'];
		$team->name_en = $input['name_en'];

		$team->save();
		return $team;
	}

 	public function update($id, $input)
	{
		$team = $this->find($id);
		
		$team->name = $input['name'];
		$team->name_en = $input['name_en'];

		$team->save();
		return $team;
	}

	public function delete($id)
	{
		$team = $this->find($id);

		$team->delete();
	}

    public function getDatatableList($searchData)
    {
		$qry = Team::select('*')->with('organization:id,name');

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
            ->addColumn('action', function ($team) {
				$permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.team'), Config::get('permission.editable'));

				$actionHtml = '<div class="dropdown dropdown-inline">';
				$actionHtml .= '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
				$actionHtml .= '<i class="fa fa-server"></i>';
				$actionHtml .= '</a>';
				$actionHtml .= '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
				$actionHtml .= '<ul class="nav nav-hoverable flex-column">';
				if($permissionEdit)
				{
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="teamEdit('.$team->id.')"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">'.trans('display.general_edit').'</span></a></li>';
					$actionHtml .= 	'<li class="nav-item"><a class="nav-link" href="#" onclick="teamDelete('.$team->id.')"><i class="nav-icon flaticon-delete"></i><span class="nav-text">'.trans('display.general_delete').'</span></a></li>';
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
