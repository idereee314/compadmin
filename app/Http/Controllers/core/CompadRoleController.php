<?php

namespace core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Input;
use Validator;
//Repositories
use user\CompadRoleRepository as Role;

//Models
use user\CompadRole as RoleModel;

class CompadRoleController extends Controller
{
    public function __construct(Role $role)
    {
        // $this->middleware('permission:'.Config::get('permission.role'));
        $this->view_path = 'core.role';
        $this->role = $role;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menus'] = @Config::get('smart.menu');
        return view($this->view_path.'.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, RoleModel::rules(0));

        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else
        {
            try
            {
                $role = $this->role->create($input);

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );

            }
            catch(\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage()
                );

            }
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->role->find($id);
        $menus = $role->menus;

        $data['role'] = $role;
        $data['roleMenus'] = $menus->pluck('operation', 'menu')->toArray();
        $data['menus'] = @Config::get('smart.menu');

        return view($this->view_path.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Input::all();
        $validator = Validator::make($input, RoleModel::rules($id));

        // process the save
        if ($validator->fails()) 
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else 
        {           
            // store
            try {
                $role = $this->role->update($id, $input);

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_update')
                );
            } 
            catch(\Illuminate\Database\QueryException $e) {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e
                );
            }            
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->role->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => 'Тухайн дүрийг ашиглаж байгаа тул устгах боломжгүй!'
            );
        }

        return $response;
    }

    public function viewMenu($id)
    {
        $role = $this->role->find($id);
        $data['menus'] = $role->menus;

        return view($this->view_path.'.view_menu', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->role->getDataList($request);
    }
}
