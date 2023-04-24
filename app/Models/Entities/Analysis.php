<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analysis extends Model {
	
	use SoftDeletes;
// 	protected $connection = 'oracle';
	protected $table = "analyses";
	protected $primaryKey = 'analysis_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'name',
			'analysis_category_id_fk',
			'code',
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
	public function category(){
		return $this->belongsTo ( 'App\Models\Entities\AnalysisCategory', 'analysis_category_id_fk', 'analysis_category_id_pk');
	}
}
