<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
	
	public function notifications()
	{
		return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
	}
	
	public function notificationRead(Request $request){
		return json_encode(array('status' => 'success'));
	}
}
