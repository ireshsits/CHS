<?php

namespace App\Helpers;
use Cache;
use UserHelper;

class CacheHelper {
	
	public static function putStatsClearKey($id=null){
		$userid = $id??UserHelper::getLoggedUserId();
		if (!Cache::has('Clear-Cache-Stats-month-'.$userid))
			Cache::put('Clear-Cache-Stats-month-'.$userid, true, 630);	//10.5 minutes	
		if (!Cache::has('Clear-Cache-Stats-annual-'.$userid))
			Cache::put('Clear-Cache-Stats-annual-'.$userid, true, 630); //10.5 minutes
		if (!Cache::has('Clear-Cache-Analyse-'.$userid))
			Cache::put('Clear-Cache-Analyse-'.$userid, true, 630); //10.5 minutes
	}
}