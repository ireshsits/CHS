<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchDepartmentUser extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="branch_department_users";
	protected $primaryKey = 'branch_department_user_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'branch_department_id_fk', 'user_id_fk', 'status'
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
			'deleted_at'
	];
	
	public function department(){
		return $this->belongsTo ( 'App\Models\Entities\BranchDepartment', 'branch_department_id_fk', 'branch_department_id_pk');
	}
	
	public function user(){
		return $this->belongsTo ( 'App\Models\Entities\User', 'user_id_fk', 'user_id_pk');
	}
}
