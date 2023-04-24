<?php

namespace App\Http\Middleware;

use Closure;
use RoleHelper;

class ComplaintEntryMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param \Closure $next        	
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$raiseRoles = RoleHelper::getComplaintRaiseRoles();
		if (! $request->user ()->hasAnyRole ( $raiseRoles )) {
			abort ( 403 );
		}
		return $next ( $request );
	}
}
