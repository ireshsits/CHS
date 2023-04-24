<?php

namespace App\Http\Controllers\Notifications;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Entities\User;
use Auth;

class PushController extends Controller
{
	
	public function __construct(){
	}
	
	/**
	 * Store the PushSubscription.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request){
		$this->validate($request,[
				'endpoint'    => 'required',
				'keys.auth'   => 'required',
				'keys.p256dh' => 'required'
		]);
		$endpoint = $request->endpoint;
		$token = $request->keys['auth'];
		$key = $request->keys['p256dh'];
		$user = Auth::user();
		$user->updatePushSubscription($endpoint, $key, $token);
		
		return response()->json(['success' => true],200);
	}
	
}