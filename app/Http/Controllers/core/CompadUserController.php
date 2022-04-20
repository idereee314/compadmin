<?php

namespace core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Input;
use Illuminate\Support\Facades\Input;
use Validator;

//Repositories
use user\CompadUserRepository as CompadUser;
use user\CompadRoleRepository as CompadRole;

//Models
use user\CompadUser as CompadUserModel;

use \Auth as Auth;
use Config;

class CompadUserController extends Controller
{
    public $restful = true;

    public function __construct(CompadUser $compadUser, CompadRole $compadRole)
    {
        $this->view_path = 'core.user';
        $this->compadUser = $compadUser;
        $this->compadRole = $compadRole;
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
        return view($this->view_path.'.add');
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

        $validator = Validator::make($input, CompadUserModel::rules(0));

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
                
                $user = $this->compadUser->create($input);

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
        $data['response'] = $response;
        return view('core.alert.messages', $data);
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
        $compadUser = $this->compadUser->find($id);
        $data['compadUser'] = $compadUser;

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

        $validator = Validator::make($input, CompadUserModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
				$users = $this->compadUser->update($id, $input);
                
				$response = array(
					'status' => 'success',
					'msg' => trans('messages.success_update')
				);
			}
			catch(Exception $e)
			{
				$response = array(
					'status' => 'error',
					'msg' => trans('messages.error_save'),
					'errors' => $e->getMessage()
				);
			}
		}

        $data['response'] = $response;
        return view('core.alert.messages', $data);
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
        
            $this->compadUser->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
    
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e
            );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->compadUser->getDatatableList($request);
    }

    public function changePassword()
    {
        $data['id'] = @Auth::user()->id;

        return view($this->view_path.'.password_change', $data);
    }

    public function checkUserPassword()
	{
		$input = Input::all();

		$compadUser = $this->compadUser->findByUserIdPassword(@Auth::user()->id, @$input['password']);

        return $compadUser;
	}   

    public function updateUserPassword($id)
    {
         $input = Input::all();

        $rules = array(
            'password' => 'required|min:8'
        );

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
				// store
				$this->compadUser->updateUserPassword($id, $input);

				$response = array(
					'status' => 'success',
					'msg' => trans('messages.success_update')
				);

			}
			catch(Exception $e)
			{
				$response = array(
					'status' => 'error',
					'msg' => trans('messages.error_save'),
					'errors' => $e->getMessage()
				);
			}
		}

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    public function searchUser()
    {
        $input = Input::all();
        $users = $this->compadUser->searchUser(@$input['q']);
        return json_encode($users);
    }

    public function roleEdit(Request $request, $id)
    {
        $user = $this->compadUser->find($id);
        $roles = $this->compadRole->all();
        $selectedRoles = $user->roles->pluck('id')->toArray();

        $data['selectedRoles'] = $selectedRoles;
        $data['roles'] = $roles;
        $data['id'] = $id;

        return view($this->view_path.'.edit_role', $data);
    }

    public function roleUpdate(Request $request, $id)
    {
        $input = Input::all();

        try {
            $user = $this->compadUser->find($id);

            if(array_key_exists('role_list', $input))
            {
                $user->roles()->sync($input['role_list']);
            }
            else
            {
                $user->roles()->detach();
            }
        
            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_save')
            );
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e->getMessage()
            );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);

    }
}
