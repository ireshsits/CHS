<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintReminder extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="complaint_reminders";
	protected $primaryKey = 'complaint_reminder_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'complaint_id_fk','reminded_by_fk', 'reminder_index', 'reminder_mode', 'reminder_date', 'reminder', 'status'
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
			'deleted_at'
	];
	
	public function complaint(){
		return $this->belongsTo('App\Models\Entities\Complaint', 'complaint_id_fk', 'complaint_id_pk');
	}   
	public function remindedBy(){
	    return $this->belongsTo('App\Models\Entities\User', 'reminded_by_fk', 'user_id_pk'); // if the value is 0, reminder is sent by the system.
	}
}
