<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintAttachment extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="complaint_attachments";
	protected $primaryKey = 'complaint_attachment_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'complaint_id_fk', 'attach_type', 'source', 'status'
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
}
