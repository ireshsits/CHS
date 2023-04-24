<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use EnumTextHelper;
use DB;

class BaseModel extends Model {
	
	public static function getPossibleEnumValues($name){
		$instance = new static; // create an instance of the model to be able to get the table name
		$type = DB::select( DB::raw('SHOW COLUMNS FROM '.$instance->getTable().' WHERE Field = "'.$name.'"') )[0]->Type;
		preg_match('/^enum\((.*)\)$/', $type, $matches);
		$enum[] = array('id' => '', 'text' => 'All');
		foreach(explode(',', $matches[1]) as $value){
			$v = trim( $value, "'" );
			$enum[] = array(
				'id' => $v,
				'text' => EnumTextHelper::getEnumText($v)
			);
		}
		return $enum;
	}
}
