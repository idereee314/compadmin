<?php

namespace App\Http\Middleware;

use Closure;
use SecurityHelper;

class PermissionMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (SecurityHelper::checkMenuPermissionByCode(@$permission)) 
        {
            return $next($request);
        }

        return redirect('/nopermission');
    }

}