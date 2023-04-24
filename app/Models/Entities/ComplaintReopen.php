<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\Complaint;
use Log;
use App\Jobs\NotificationJob;
use EnumTextHelper;
use App\Traits\CustomConfigurationService;

class ComplaintReopen extends Model
{
    use SoftDeletes;
    use CustomConfigurationService;

//     protected $connection = 'oracle';
    protected $table = "complaint_reopens";
    protected $primaryKey = 'complaint_reopen_id_pk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_id_fk', 'reopen_by_id_fk', 'reopen_reason','status'
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

    public function reopnedBy()
    {
        return $this->belongsTo('App\Models\Entities\User', 'reopen_by_id_fk', 'user_id_pk');
    }
    
    public static function updateStatus($id, $data, $mode='ALL') {
        /**
         * $id contain a complaint reopens table reference.
         * Update all the escalations related to that complaint.
         * call from complaintSolution
         */
        switch($mode){
            case 'ALL': parent::where('complaint_id_fk', $id)->update(['status' => $data['status']]);break;
            case 'ONE': parent::where('complaint_reopen_id_pk',$id)->update(['status' => $data['status']]);break;
            default	  : null;break;
        }
    }
    
    public function save(array $options = Array()) {
        $dirty = $this->getDirty ();
        parent::save ();
        /**
         *
         */
        Log::info('Complaint Reopen dirty >>'.json_encode ($dirty));
        if (isset ( $dirty ['status'] ) || isset ( $dirty ['deleted_at'] )) {
            self::sendNotifications ($this->complaint_id_fk, 'RECEPIENT', -1, 'REOPN'); // CR4
            /**
             * Determine the type of the reopen based on the status of the complaint currently in, 
             */
            $this->type = Complaint::find($this->complaint_id_fk)->status;
            parent::save ();
           return $this->updateComplaintStatus($this->complaint_id_fk, $this->status);
        }
        return parent::save ();
    }
    
    /**
     * Update complaint status according to reply
     * @param unknown $complaintId
     * @param unknown $status
     * @return unknown
     */
    private function updateComplaintStatus($complaintId,$status){
        $compaintStatus = $this->getOverridedComplaintStatus($status);
        if($compaintStatus)
        return Complaint::updateStatus($complaintId,array('status' => $compaintStatus));
    }
    
    private function getOverridedComplaintStatus($status){
        switch ($status) {
            // case 'INP' : return 'INP';break;
            case 'INP' : return 'PEN';break; // CR4
            default	   : return null;break;
        }
    }

    /**
    * Send Notifications based on services
    *\
    * @param unknown $id
    * @param string $action
    */
    public static function sendNotifications($id, $role = 'ALL', $subId = -1, $overrideStatus = null, $schedule = false, $action = 'notification') {
        // check for report purpose complaint
		$isReportingComplaint = (new static)->getReportPurposeComplaintStatus(['complaint_id' => $id]);
		if (!$isReportingComplaint) {
            /**
             * mode = new/reminder
             */
            Log::info(' --------------------------------------------------------------------------------------------------------------------------- ');
            Log::info('Triggering MailService for >> complaintId='.$id.' >> role='.$role.' >> subId='.$subId.' >> overrideStatus='.$overrideStatus);
            NotificationJob::dispatch ( array (
                    'action' => $action,
                    'complainId' => $id,
                    'notifyRole' => $role,
                    'subModuleId' => $subId,
                    'override_status' => $overrideStatus,
                    'schedule' => $schedule,
                    'loggedUser' => auth()->user()
            ) );
        }
    }
    
}
