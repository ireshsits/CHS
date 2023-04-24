<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolutionHistory extends Model
{
    use SoftDeletes;
    
    //     protected $connection = 'oracle';
    protected $table="solution_histories";
    protected $primaryKey = 'solution_history_id_pk';
    
    /**
     * Avoid created_at and updated_at automatically get values.
     * @var boolean
     */
//     public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_solution_id_fk', 'owner_id_fk', 'resolved_by_fk', 'action_taken', 'status' , 'created_at', 'updated_at', 'deleted_at'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];
    
    public function solution(){
        return $this->belongsTo('App\Models\Entities\ComplaintSolution', 'complaint_solution_id_fk', 'complaint_solution_id_pk');
    }
    
}
