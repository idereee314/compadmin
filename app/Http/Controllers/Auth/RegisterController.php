<?php namespace Auth;

use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Input;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

//Repository
use core\UserRepository as User;
use core\UserChangeRepository as UserChange;

//Model
use core\User as UserModel;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct(User $user, UserChange $userChange)
	{
		$this->user = $user;
        $this->userChange = $userChange;
        $this->view_path = "auth";
    }

    protected function create()
    {
        return view($this->view_path.'.register');
    }

    protected function createPassword()
    {
        return view($this->view_path.'.createPassword');
    }

    protected function doPassword(Request $request)
    {
        $input = Input::all();

        try
        {
            $user = $this->user->find($input['userId']);
            if($input['password'] == $input['repeatPassword'])
            {
                $user->password = md5(@$input['password']);
                $user->is_verified = 1;
                $user->save();

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
            }
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $e->getMessage()
            );

        }

        return response()->json($response);
    }

    protected function store(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, UserModel::$rules);

        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else {

                $user = $this->user->create($input);

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);

    }

    public function verifyUser(Request $request)
    {
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
        $user = $this->user->findByVerificationCode($verification_code);

        if($user != null) 
        {
            if($user->is_verified == 1) 
            {
                return Inertia::render('Login/Index');
            }
            else 
            {
                // $user->is_verified = 1;
                // $user->save();
                $status = true;
                $response['userId'] = $user->user_id;
            }
        } 
        else
        {
            $status = false;
            $msg = 'Холбоос буруу байна';
        }
        
        $response['status'] = @$status;
        $response['msg'] = @$msg;

        return Inertia::render('Login/CreatePassword', [
            'response' => $response 
        ]);
    }

    public function verifyChangeEmail(Request $request)
    {
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
        $userChange = $this->userChange->findByVerificationCode($verification_code);

        if(!empty($userChange)) 
        {
            $this->user->updateUsernameEmail($userChange);

            $status = true;
            $msg = "Амжжилттай баталгаажууллаа";
        } 
        else
        {
            $status = false;
            $msg = 'Холбоос буруу байна';
        }
        
        $response['status'] = @$status;
        $response['msg'] = @$msg;

        return Inertia::render('Login/Index', [
            'newEmail' => $userChange->new_email 
        ]);
    }
}



