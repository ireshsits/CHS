<?php

namespace App\Helpers;

class AnalysisHelper {
	
	public static function generateWeightage($sum, $valueTotal){
		if($sum > 0 && $valueTotal > 0)
			return round(((float) $valueTotal/ (float) $sum )*100 , 3);
		return round((float) 0,3);
	}
	
	public static function generateSum($sum, $value){
		return (int) $sum += (int) $value ;
	}
}