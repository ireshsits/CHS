<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalysisCategory extends Model {
	
	use SoftDeletes;
// 	protected $connection = 'oracle';
	protected $table = "analysis_categories";
	protected $primaryKey = 'analysis_category_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'name',
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
	public function analyses() {
		return $this->hasMany ( 'App\Models\Entities\Analysis', 'analysis_category_id_fk', 'analysis_category_id_pk' );
	}
}
