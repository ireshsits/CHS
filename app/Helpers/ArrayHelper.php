<?php

namespace App\Helpers;

class ArrayHelper {
	
	public static function toSingleQuote($array){
// 		$new_array = [];
// 		foreach($array as $key => $value) {
// 			$new_array[str_replace('""', '', $key)] = str_replace('""', '', $value);
// 		}
// 		return $new_array;
		
		$o = [];
		foreach($array as $k=>$v){
			if(is_array($v)){
				$o[trim($k,"\"'")] = self::toSingleQuote($v);
			}else{
				$o[trim($k,"\"'")] = trim($v,"\"'");
			}
			
		}
		return $o;
	}
	public static function jsonToSingleQuote($string){
		return preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $string);
	}
}