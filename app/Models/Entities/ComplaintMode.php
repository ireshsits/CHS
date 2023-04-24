<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintMode extends Model {
	use SoftDeletes;
// 	protected $connection = 'oracle';
	protected $table = "complaint_modes";
	protected $primaryKey = 'complaint_mode_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'name',
			'code',
			'color',
			'status' 
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [ 
			'deleted_at' 
	];
	public function complaints() {
		return $this->hasMany ( 'App\Models\Entities\Complaint', 'complaint_mode_id_fk', 'complaint_mode_id_pk' );
	}
	
	/**
	 * Return complaint mode code for the saving of the complaint reference number.
	 */
	public static function getModeCode($id){
		return parent::where('complaint_mode_id_pk', $id)->first()->code;
	}
}
