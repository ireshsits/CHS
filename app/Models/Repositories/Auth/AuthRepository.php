<?php

namespace App\Models\Repositories\Auth;

use App\Models\Entities\User;
use Auth;

class AuthRepository implements AuthInterface {
	public function __construct(){
		
	}
	public function authUser(){
		return Auth::user();
	}
}