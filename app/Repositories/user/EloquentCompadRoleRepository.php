<?php namespace user;

use user\CompadRole;
use user\CompadUser as User;
use user\CompadRoleMenu;
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

	public function create($input)
    {
        $roleMenus = array();
        $role = new CompadRole();
        $role->name = $input['name'];
		$role->code = $input['code'];

        $role->save();

        foreach(@$input['menus'] as $key => $menu)
        {
            if(@$menu)
            {
				$menuArr['role_id'] = $role->id;
				$menuArr['menu'] = $key;

				$menuIn['role_id'] = $role->id;
				$menuIn['menu'] = $key;
				$menuIn['operation'] = $menu;

			    $role->menus()->updateOrCreate($menuArr, $menuIn);
            }
        }
        return $role;
    }

    public function update($id, $input)
    {
        $role = $this->find($id);
		$role->name = $input['name'];
		$role->code = $input['code'];
        $role->save();

        foreach(@$input['menus'] as $key => $menu)
        {
            if(@$menu)
            {
				$menuArr['role_id'] = $role->id;
				$menuArr['menu'] = $key;

				$menuIn['role_id'] = $role->id;
				$menuIn['menu'] = $key;
				$menuIn['operation'] = $menu;

			    $role->menus()->updateOrCreate($menuArr, $menuIn);
            }
        }
        return $role;
    }

    public function delete($id)
    {
        $role = CompadRole::find($id);
        $role->delete();
    }

    public function getDataList($searchData)
    {
        $qry = CompadRole::select('*')->with('menus');

        $data = DataTables::make($qry)
        ->editColumn('created_at', function($role)
        {
            return $role->created_at;
        })
        ->addColumn('menu_count', function($role)
        {
            return '<span style="cursor:pointer" onclick="showMenu('.$role->id.')" class="label label-lg font-weight-bolder label-rounded label-success">'.count($role->menus).'</span>';
        })
        ->addColumn('action', function ($role) {
            $permissionEdit = SecurityHelper::checkPermission(@Config::get('permission.role'), Config::get('permission.editable'));

            $actionHtml = "";

            $actionHtml .=  '<div class="dropdown dropdown-inline">';
            $actionHtml .=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown">';
            $actionHtml .=  '<i class="fas fa-server"></i> </a>';
            $actionHtml .=  '<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">';
            $actionHtml .=  '<ul class="nav nav-hoverable flex-column">';

            if($permissionEdit && Auth::user()->roles->first()->code == 'admin')
            {
                $actionHtml .=  '<li class="nav-item"><a class="nav-link edit" href="javascript:;" data-roleid="'.$role->id.'"><i class="nav-icon flaticon-edit-1"></i><span class="nav-text">Засварлах</span></a></li>';
                $actionHtml .=  '<li class="nav-item"><a class="nav-link delete" href="javascript:;" data-roleid="'.$role->id.'"><i class="nav-icon flaticon2-trash"></i><span class="nav-text">Устгах</span></a></li>';
          	}

            $actionHtml .=  '</ul>';
            $actionHtml .=  '</div>';
            $actionHtml .=  '</div>';

            return $actionHtml;
        })
        ->rawColumns(['action', 'menu_count'])
        ->make(true);

        return $data;
    }
}
