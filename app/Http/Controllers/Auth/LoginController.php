<?php

namespace Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use SmartHelper;
use Config;

//Repositories
use user\CompadUserRepository as User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(User $user)
	{
		$this->user = $user;
    }

    public function doLogin()
    {
        $input = Request::all();

		$rules = array(
			'username'  => 'required',
			'password' => 'required'
        );

		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			Session::flash('message', trans('messages.error_login'));

			return Redirect::to('/')
				->withErrors($validator)
				->withInput(Request::except('password'));
		} else {

			$userdata = array(
				'username' 	=> Request::get('username'),
				'password' 	=> Request::get('password')
			);

			$foundUser = $this->user->findByUsernamePassword($userdata['username'], $userdata['password']);

			if ($foundUser != null) {

				Auth::login($foundUser);

				Session::put('firstname', $foundUser->firstname);
				Session::put('lastname', @$foundUser->lastname);

				return Redirect::intended('/home');

			} else {
				Session::flash('message', trans('messages.error_login'));
				return Redirect::to('/');
			}
		}
    }

	public function doLogout()
	{
		// if(SmartHelper::checkRedis())
		// {
		// 	SmartHelper::removeUserFromRedis(Auth::user()->id);
		// }

		Auth::logout();
		Session::flush();

    	return Redirect::to('/');
	}

	public function showLogin() {
        return view('auth.login');
	}
}
