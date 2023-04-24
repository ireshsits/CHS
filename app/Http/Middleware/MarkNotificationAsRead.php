<?php

namespace App\Http\Middleware;

use Closure;

class MarkNotificationAsRead {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param \Closure $next        	
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if ($request->route('id')) {
			$notification = $request->user ()->notifications ()->where ( 'id', $request->id )->first ();
			if ($notification) {
				$notification->markAsRead ();
				return redirect($notification->data['data']['url']);
			}else{
				return response()->json('Notification not found.', 404);
			}
		}
		return $next ( $request );
	}
}
