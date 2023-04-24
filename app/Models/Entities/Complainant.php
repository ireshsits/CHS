<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complainant extends Model
{
	use SoftDeletes;
	
//     protected $connection = 'oracle';
    protected $table="complainants";
    protected $primaryKey = 'complainant_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'first_name', 'last_name', 'nic', 'contact_no', 'status'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    		'deleted_at'
    ];
    
    public function complaints(){
    	return $this->hasMany('App\Models\Entities\Complaint', 'complainant_id_fk', 'complainant_id_pk');
    }
    
}
