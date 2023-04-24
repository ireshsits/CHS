<?php

namespace App\Http\Middleware;

use Closure;
use RoleHelper;

class ReportsManagement
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
//     	$CCCRoles = RoleHelper::getCCCRoles ();
        $adminRoles = RoleHelper::getAdminViewRoles ();
        if (! $request->user ()->hasAnyRole ( $adminRoles)) {
    		abort ( 403 );
    	}
        return $next($request);
    }
}
