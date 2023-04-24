<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use RoleHelper;

class Zone extends Model {
    /**
     * Zone model will be use for both the zonal manager and chief manager purpose. zonal manager has set of regions while chief manager has set of departments
     */
	use SoftDeletes;
// 	protected $connection = 'oracle';
	protected $table = "zones";
	protected $primaryKey = 'zone_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
			'name','number', 'manager_id_fk', 'reject_reason', 'rejected_by_fk', 'status' 
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [ 
			'deleted_at' 
	];
	/**
	 * Added for the use of zonal manager
	 * @return unknown
	 */
	public function regions() {
		return $this->hasMany ( 'App\Models\Entities\Region', 'zone_id_fk', 'zone_id_pk' );
	}
	
	/**
	 * Added for the use of Chief manager
	 * @return unknown
	 */
	public function departments() {
	    return $this->hasMany ( 'App\Models\Entities\BranchDepartment', 'zone_id_fk', 'zone_id_pk' );
	}
	
	public function manager(){
	    return $this->belongsTo ( 'App\Models\Entities\User', 'manager_id_fk', 'user_id_pk');
	}
	
	public function rejectedBy(){
	    return $this->belongsTo ( 'App\Models\Entities\User', 'rejected_by_fk', 'user_id_pk');
	}
	
	public function save(array $options = Array()) {
	    $dirty = $this->getDirty ();
	    $originalManagerId = $this->getOriginal ( 'manager_id_fk' );
	    parent::save ();
	    /**
	     *
	     */
	    Log::info('Zone dirty >>'.json_encode ($dirty));
	    if (isset ( $dirty ['status'] ) || isset ( $dirty ['deleted_at'] )) {
	    }
	    
	    /**
	     * Assign back the user role to the removing manager
	     */
	    if($originalManagerId !== null && $originalManagerId !== $this->manager_id_fk){
	        self::syncRole(RoleHelper::getUserRole(),$originalManagerId);
	    }
	    /**
	     * Assign zm role.
	     */
	    if($this->manager_id_fk != null)
	        self::syncRole(RoleHelper::getZonalManagerRole(),$this->manager_id_fk);
	    
	    return parent::save ();
	}
	
	/**
	 * Call Before the event get fired
	 */
	public static function boot() {
	    parent::boot();
	    static::deleting(function($zone){
	        self::syncRole(RoleHelper::getUserRole(),$zone->manager_id_fk);
	    });
	    static::saving(function($zone){
        });
	    static::updating(function($zone){
        });
	}	
	
	private static function syncRole($role,$id){
	    $user = User::find($id);
	    if($user)
	       $user->syncRoles([$role]);
	}
}
