<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Illuminate\Support\Facades\DB;

class ComplaintNotificationOtherUser extends Model
{
    
    use SoftDeletes;

    protected $table="complaint_notification_other_users";
	protected $primaryKey = 'complaint_notification_other_user_id_pk';

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'complaint_id_fk', 
        'user_id_fk'
    ];
    /**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
        'deleted_at'
    ];

    protected $with = array('user');

    public function complaint(){
		return $this->belongsTo('App\Models\Entities\Complaint', 'complaint_id_fk', 'complaint_id_pk');
	}
	public function user(){
		return $this->belongsTo('App\Models\Entities\User', 'user_id_fk', 'user_id_pk' );
	}

}
