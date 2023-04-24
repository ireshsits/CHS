<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\Complaint;

class SolutionAmendment extends Model
{
    use SoftDeletes;
    
//     protected $connection = 'oracle';
    protected $table = "solution_amendments";
    protected $primaryKey = 'solution_amendment_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_solution_id_fk', 'amendment_by_fk', 'amendment','status'
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
    
    public function amendmentBy(){
        return $this->belongsTo('App\Models\Entities\User', 'amendment_by_fk', 'user_id_pk'); // Not used ComplaintUser table id, asthis feature may used by call centre admin also. CCC admin not belongs to complaint. so not in ComplaintUser table
    }
    
    public static function updateStatus($id, $data, $mode='ALL') {
        switch($mode){
            case 'ALL': parent::where('complaint_solution_id_fk', $id)->update(['status' => $data['status']]);break;
            case 'ONE': parent::where('solution_amendment_id_pk',$id)->update(['status' => $data['status']]);break;
            default	  : null;break;
        }
    }    
    
    public function save(array $options = Array()) {
        $dirty = $this->getDirty ();
        parent::save ();
        
        /**
         * Notifications.
         */
        if (isset ( $dirty ['status'] )){
             Complaint::sendNotifications ( $this->solution->complaint_id_fk, 'AMENDMENT', $this->solution_amendment_id_pk ,$this->status);
        }
        
        return parent::save();
    }
    
    /**
     * Call Before the event get fired
     */
    public static function boot() {
        parent::boot ();
        
        static::deleting ( function ($solutionAmendment) {
            Complaint::sendNotifications ( $solutionAmendment->solution->complaint_id_fk, 'AMENDMENT', $solutionAmendment->solution_amendment_id_pk ,'DEL');
        } );
            
        static::saving ( function ($solutionAmendment) {
        });
                
        static::updating(function($solutionAmendment){
        });
    }
}
