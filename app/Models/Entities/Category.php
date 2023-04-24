<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cache;

class Category extends Model
{
	use SoftDeletes;
	
//     protected $connection = 'oracle';
    protected $table="categories";
    protected $primaryKey = 'category_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'name','color','parent_category_id','category_level','remarks','reject_reason','rejected_by_fk','status'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    		'deleted_at'
    ];
    
//     public function subCategories(){
//     	return $this->hasMany('App\Models\Entities\SubCategory', 'category_id_fk', 'category_id_pk');
//     }
    public function childCategories(){
        return $this->hasMany('App\Models\Entities\Category','parent_category_id', 'category_id_pk');
    }
    
    public function parentCategory(){
        return $this->belongsTo('App\Models\Entities\Category','parent_category_id', 'category_id_pk');
    }
    
    public function rejectedBy(){
    	return $this->belongsTo('App\Models\Entities\User', 'rejected_by_fk', 'user_id_pk');
    }
    
    public function complaints(){
        return $this->hasMany('App\Models\Entities\Complaint','category_id_pk', 'category_id_fk');
    }
    
    /**
     * Call Before the event get fired
     */
    public static function boot() {
    	parent::boot();
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
