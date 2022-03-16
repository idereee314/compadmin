<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Input;

//Repositories
use core\UserRepositoryInterface as User;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(User $user)
	{
		$this->user = $user;
    }

    public function handle(Request $request, Closure $next)
    {
        $foundUser = $this->user->findByUsernamePassword($request->getUser(), $request->getPassword());

        if($foundUser) 
        {
            $request->merge(array("id" => $foundUser->user_id));
            return $next($request);
        } 
        else 
        {
            return response()->json('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }
    
    }
}
