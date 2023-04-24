<?php

namespace App\Models\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
	use Notifiable, HasRoles, SoftDeletes, HasPushSubscriptions;
    
//     protected $connection = 'oracle';
    protected $table="users";
    protected $primaryKey = 'user_id_pk';
    
//     /**
//      * The channels the user receives notification broadcasts on.
//      *
//      * @return string
//      */
//     public function receivesBroadcastNotificationsOn()
//     {
//     	return 'User.'.$this->id;
//     }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'emp_id', 'email', 'username', 'password', 'active', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    		'deleted_at'
    ];
    
    public function departmentUser(){
    	return $this->hasOne('App\Models\Entities\BranchDepartmentUser', 'user_id_fk', 'user_id_pk');
    }
    
    public function complaints(){
        return $this->hasMany('App\Models\Entities\ComplaintUser', 'user_id_fk', 'user_id_pk');
    }
    
    public function complaintReopens(){
        return $this->hasMany('App\Models\Entities\ComplaintReopen', 'reopen_by_id_fk', 'user_id_pk');
    }
    
    public function branchManager()
    {
        return $this->hasOne('App\Models\Entities\BranchDepartment', 'manager_id_fk');
    }
    
    public function regionalManager()
    {
        return $this->hasOne('App\Models\Entities\Region', 'manager_id_fk');
    }
    
    public function zonalManager()
    {
        return $this->hasOne('App\Models\Entities\Zone', 'manager_id_fk');
    }
}
