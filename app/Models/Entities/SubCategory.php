<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\CacheKeyGenerateJob;

class SubCategory extends Model
{
	use SoftDeletes;
	// not using after CR3
//     protected $connection = 'oracle';
    protected $table="sub_categories";
    protected $primaryKey = 'sub_category_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'category_id_fk','name','color','area_id_fk','reject_reason','rejected_by_fk','status'
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
    	return $this->belongsTo ( 'App\Models\Entities\Category', 'category_id_fk', 'category_id_pk');
    }
    
    public function area(){
    	return $this->belongsTo ( 'App\Models\Entities\Area', 'area_id_fk', 'area_id_pk');
    }
    
    public function rejectedBy(){
    	return $this->belongsTo('App\Models\Entities\User', 'rejected_by_fk', 'user_id_pk');
    }
    
    /**
     * Call Before the event get fired
     */
    public static function boot() {
		parent::boot ();
		static::deleting ( function ($complaint) {
// 			CacheKeyGenerateJob::dispatch();
		} );
		static::saving ( function ($complaint) {
// 			CacheKeyGenerateJob::dispatch();
		} );
		static::updating ( function ($complaint) {
// 			CacheKeyGenerateJob::dispatch();
    	} );
    }	
    
}
