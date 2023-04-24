<?php

namespace App\Http\Middleware;

use Closure;
use RoleHelper;

class SiteManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adminRoles = RoleHelper::getAdminRoles ();
        if (! $request->user ()->hasAnyRole ( $adminRoles)) {
    		abort ( 403 );
    	}
    	return $next($request);
    }
}
