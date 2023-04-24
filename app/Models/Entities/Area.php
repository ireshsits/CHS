<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cache;

class Area extends Model {
	use SoftDeletes;
// 	protected $connection = 'oracle';
	protected $table = "areas";
	protected $primaryKey = 'area_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'name', 'color', 'reject_reason','rejected_by_fk','status'
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [ 
			'deleted_at' 
	];
// 	public function subCategories() {
// 		return $this->hasMany ( 'App\Models\Entities\SubCategory', 'area_id_fk', 'area_id_pk' );
// 	}

	public function complaints(){
	    return $this->hasMany('App\Models\Entities\Complaint','area_id_pk', 'area_id_fk');
	}
	
	/**
	 * Call Before the event get fired
	 */
	public static function boot() {
		parent::boot ();
		static::deleting ( function ($complaint) {
			Cache::put ( 'Clear-Cache', true );
		} );
		static::saving ( function ($complaint) {
			Cache::put ( 'Clear-Cache', true );
		} );
		static::updating ( function ($complaint) {
			Cache::put ( 'Clear-Cache', true );
		} );
	}	
}
