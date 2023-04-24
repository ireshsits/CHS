<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchDepartment extends Model
{
	use SoftDeletes;
	
//     protected $connection = 'oracle';
    protected $table="branch_departments";
    protected $primaryKey = 'branch_department_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region_id_fk','zone_id_fk', 'sol_id', 'name', 'manager_id_fk', 'type', 'status'
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
     * uses if the branch map to regional manager (RM)
     * @return unknown
     */
    public function region(){
    	return $this->belongsTo ( 'App\Models\Entities\Region', 'region_id_fk', 'region_id_pk');
    }
    
    /**
     * uses if the department map to zone for chief manager (CM)
     * @return unknown
     */
    public function zone(){
        return $this->belongsTo ( 'App\Models\Entities\Zone', 'zone_id_fk', 'zone_id_pk');
    }
    
    public function users(){
    	return $this->hasMany('App\Models\Entities\BranchDepartmentUser', 'branch_department_id_fk', 'branch_department_id_pk');
    }
    
    public function manager(){
        return $this->belongsTo ( 'App\Models\Entities\User', 'manager_id_fk', 'user_id_pk');
    }
}
