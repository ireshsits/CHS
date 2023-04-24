<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\Complaint;
use Log;

class ComplaintClose extends Model
{
    use SoftDeletes;
    
//     protected $connection = 'oracle';
    protected $table = "complaint_closes";
    protected $primaryKey = 'complaint_close_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_id_fk', 'closed_by_id_fk', 'remarks','status'
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
    
    public function closedBy(){
        return $this->belongsTo('App\Models\Entities\User', 'closed_by_id_fk', 'user_id_pk');
    }
    
    public static function updateStatus($id, $data, $mode='ALL') {
        /**
         * $id contain a complaint completion table reference.
         */
        switch($mode){
            case 'ALL': parent::where('complaint_id_fk', $id)->update(['status' => $data['status']]);break;
            case 'ONE': parent::where('complaint_close_id_pk',$id)->update(['status' => $data['status']]);break;
            default	  : null;break;
        }
    }
    
    public function save(array $options = Array()) {
        $dirty = $this->getDirty ();
        parent::save ();
        /**
         *
         */
        Log::info('Complaint Completion dirty >>'.json_encode ($dirty));
        if (isset ( $dirty ['status'] ) || isset ( $dirty ['deleted_at'] )) {
            
        }
        return parent::save ();
    }
}
