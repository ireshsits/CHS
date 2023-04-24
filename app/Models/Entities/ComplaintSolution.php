<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintEscalation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Log;
use UserHelper;

class ComplaintSolution extends Model
{
    use LogsActivity, SoftDeletes, CascadeSoftDeletes;
	
//     protected $connection = 'oracle';
    protected $table="complaint_solutions";
    protected $primaryKey = 'complaint_solution_id_pk';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_id_fk', 'owner_id_fk', 'resolved_by_fk', 'action_taken', 'status'
    ];
    
    /**
     * Logged the activities related tot he given fields
     * @var array
     */
    protected static $logAttributes = ['complaint_id_fk','owner_id_fk','resolved_by_fk', 'action_taken', 'status'];
    
    /**
     * given child rows will be deleted
     */
    protected $cascadeDeletes = ['histories'];
    
    
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
    public function complaintOwner(){
        return $this->belongsTo('App\Models\Entities\ComplaintUser', 'owner_id_fk', 'complaint_user_id_pk'); // use to detect the owner of the compaint within the escalation flow.
    }
    public function resolvedBy(){
    	return $this->belongsTo('App\Models\Entities\ComplaintUser', 'resolved_by_fk', 'complaint_user_id_pk'); //change the id to complaintUser user
    }
    public function resolvedByUser(){
        return $this->belongsTo('App\Models\Entities\ComplaintUser', 'resolved_by_fk', 'complaint_user_id_pk')->withTrashed();
    }
    public function amendments(){
        return $this->hasMany('App\Models\Entities\SolutionAmendment', 'complaint_solution_id_fk', 'complaint_solution_id_pk');
    }
    public function histories(){
        return $this->hasMany('App\Models\Entities\SolutionHistory', 'complaint_solution_id_fk','complaint_solution_id_pk');
    }
    
    public static function updateStatus($id, $data, $mode='ALL') {
        /**
         * $id contain a Escalation table reference.
         * Update all the escalations related to that complaint.
         * call from complaintSolution
         */
        switch($mode){
            case 'ALL': 
                $solutions = parent::where('complaint_id_fk', $id)->get();
                foreach ($solutions as $solution){
                    $solution->fill($data)->save(array('ComplaintUpdated' => true));
                }
                break;
            case 'ONE': parent::where('complaint_solution_id_pk',$id)->update(['status' => $data['status']]);break;
            default	  : null;break;
        }
    }
    
    public function save(array $options = Array()) {
    	$dirty = $this->getDirty ();
    	parent::save ();
    	/**
    	 * Double check if this get fired in here -> isset ( $dirty ['deleted_at'] )
    	 */
    	Log::info('Solution dirty >>'.json_encode ($dirty));
    	if (isset ( $dirty ['status'] )) {
    	    
    	    /**
    	     * State change from ACP/VFD to NACP will not change the escalation info as its not deleting the solution.
    	     * After changing the solution to be kept in draft mode, old_complaint_escalation_id_pk will not be available in verfy mode.
    	     * Due to that had to check in DB if there is any INP escaltion to be completed with the solution.
    	     * Ignore proceess in Draft mode
    	     */
    	    if($dirty ['status'] != 'PEN'){
//     	        if(isset($options['old_complaint_escalation_id_pk'])){
    	        $escalation = ComplaintEscalation::where('complaint_id_fk', $this->complaint_id_fk)->where('status', 'INP')
    	                       ->whereHas('escalatedTo.user', function($q){
    	                           $q->where('user_id_pk', UserHelper::getLoggedUserId());
    	                       })->first();
    	            /**
    	             * Update early escalation(s)
    	             */
    	          if($escalation){
//     	            $this->updateEscalationStatus($options['old_complaint_escalation_id_pk'], $this->status);
    	              $this->updateEscalationStatus($escalation->complaint_escalation_id_pk, $this->status);
    	            /**
    	             * If user is a escalated, get the owner id by escalation owner info
    	             */
//     	            $this->owner_id_fk = ComplaintEscalation::find($options['old_complaint_escalation_id_pk'])->owner_id_fk;
    	              $this->owner_id_fk = ComplaintEscalation::find($escalation->complaint_escalation_id_pk)->owner_id_fk;
    	            parent::save ();
    	          }
    	    }
    	    
    	    if($dirty ['status'] != 'NACP'){
    	        $user = ComplaintUser::where('complaint_id_fk',$this->complaint_id_fk)->where('user_id_fk',UserHelper::getLoggedUserId())->first();
    	        if($user->user_role == 'OWNER'){
    	            $this->owner_id_fk = $user->complaint_user_id_pk;
    	            parent::save ();
    	        }
    	   }
    	   
    	   $this->updateComplaintUserStatus($this->resolved_by_fk, $this->status);
    	   /**
    	    * Ignore proceess in Draft mode
    	    */
    	   if($dirty ['status'] != 'PEN'){
    	    if(!isset($options['ComplaintUpdated']))
    	        $this->updateComplaintStatus($this->complaint_id_fk, $this->status, parent::where ( 'complaint_id_fk', $this->complaint_id_fk )->whereIn('status',['ACP','VFD'])->count ());
    	   }
    	}
    	
    	/**
    	 * Notifications.
    	 * Ignore proceess in Draft mode
    	 */
    	if (isset ( $dirty ['status'] ) && $this->status != 'PEN'){
    	   Complaint::sendNotifications ( $this->complaint_id_fk, 'SOLUTION', $this->complaint_solution_id_pk ,$this->status);
    	}
    	
    	return parent::save ();
	}
	
	/**
	 * Call Before the event get fired
	 */
	public static function boot() {
		parent::boot ();
		
		static::deleting ( function ($complaintSolution) {
		    Log::info('Solution deleting >>'.json_encode ($complaintSolution));
		    ComplaintUser::updateStatus($complaintSolution->resolved_by_fk,array('status' => 'INP'),'ONE');
		    /**
		     * Ignore proceess in Draft mode
		     */
		    if($complaintSolution->status !== 'PEN'){
		        /**
		         * Check if any COMPLETED escalations related to this user. if exists change them to INP with others mark as PASSED.
		         */
		        $escalation = ComplaintEscalation::where('complaint_id_fk', $complaintSolution->complaint_id_fk)->where('escalated_to_fk',$complaintSolution->resolved_by_fk)->whereIn('status',['INP','COM'])->first();
		        if($escalation){
		            ComplaintEscalation::updateStatus($escalation->complaint_escalation_id_pk,array('status' => 'INP'));
		        }
		        /**
		         * Need to aoid this process if it contain multiple solutions.
		         * Pass 0 as the solution as this solution will get deleted after the status update
		         */
		        $solutionsCount = parent::where ( 'complaint_id_fk', $complaintSolution->complaint_id_fk )->whereIn('status',['ACP','VFD'])->count ();
		        // 			if (! $solutionsCount > 1)
		            // 			return parent::updateComplaintStatus ( $complaintSolution->complaint_id_fk, 'DEL', 0);
		        Complaint::updateStatus($complaintSolution->complaint_id_fk,array('status' => 'INP', 'solutionCount' => ((int)$solutionsCount-1)));
		        /**
		         * Notify owner
		         */
		        Complaint::sendNotifications ( $complaintSolution->complaint_id_fk, 'SOLUTION', $complaintSolution->complaint_solution_id_pk ,'DEL');
		    }
		} );
		
		static::saving ( function ($complaintSolution) {
    	});
    			
		static::updating(function($complaintSolution){
    	});
    }	
    
    /**
     * Update complaint status according to reply
     * @param unknown $complaintId
     * @param unknown $status
     * @return unknown
     */
    private function updateComplaintStatus($complaintId,$status,$count){
    	$compaintStatus = $this->getOverridedComplaintStatus($status);
    	return Complaint::updateStatus($complaintId,array('status' => $compaintStatus, 'solutionCount' => $count));
    }
    
    /**
     * Update escalation Status
     */
    private function updateEscalationStatus($escalatedId,$status){
    	$escalteStatus = $this->getOverridedEscalateStatus($status);
    	return ComplaintEscalation::updateStatus($escalatedId,array('status' => $escalteStatus));
    }
    
    /**
     * Update complaint owner Status
     */
    private function updateComplaintUserStatus($complaintUserId,$status){
        $userStatus = self::getOverridedComplaintUserStatus($complaintUserId,$status);
        return ComplaintUser::updateStatus($complaintUserId,array('status' => $userStatus),'ONE');
    }
    
    
    private function getOverridedEscalateStatus($status){
    	switch ($status) {
    		case 'PEN'	: return 'INP';break;
    		case 'VFD'	: return 'COM';break;
    		case 'ACP'	: return 'COM';break;
    		case 'NACP'	: return 'INP';break;
    		/**
    		 * Override status
    		 */
    		case 'DEL'	: return 'INP';break;
    		default	  	: return null;break;
    	}
    }
    
    private function getOverridedComplaintStatus($status){
    	switch ($status) {
    	    case 'PEN'	: return 'INP';break; //done - was REP. now passing as RESPONDED
    	    case 'VFD'	: return 'REP';break; //done earlier COM, after was REP. now passing as RESPONDED.
    		case 'ACP'	: return 'REP';break; //done earlier COM, after was REP. now passing as RESPONDED.
    		case 'NACP' : return 'INP';break;
    		/**
    		 * Override status
    		 */
    		case 'DEL'	: return 'INP';break;//done
    		default	  	: return null;break;
    	}
    }
    
    private function getOverridedComplaintUserStatus($complaintUserId,$status){
        switch ($status) {
            case 'PEN'	: return $this->checkCurrentUserStatus($complaintUserId,'PEN');break; //after was REP. now passing as RESPONDED
            case 'VFD'	: return $this->checkCurrentUserStatus($complaintUserId,'VFD');break; //done earlier COM, after was REP. now passing as RESPONDED.
            case 'ACP'	: return $this->cheREPckCurrentUserStatus($complaintUserId,'ACP');break; //done earlier COM, after was REP. now passing as RESPONDED.
            case 'NACP' : return 'INP';break;
            /**
             * Override status
             */
//             case 'DEL'	: return 'INP';break; //done
            case 'DEL'  : return $this->checkCurrentUserStatus($complaintUserId,'DEL'); break;
            default	  	: return null;break;
        }
    }
    
    private function checkCurrentUserStatus($complaintUserId, $status){
        $user = ComplaintUser::find($complaintUserId);
        if($status == 'ACP' || $status == 'VFD'){
            switch ($user->status) {
                case 'INP'	: return 'REP';break;
                case 'REPP' : return 'REP';break;
                case 'ESC'  : return 'EREP';break; 
                case 'EREP'	: return 'EREREP';break;
                case 'REP'	: return 'REP';break;
                default	  	: return null;break;
            }
        }
        if($status == 'PEN'){
            switch ($user->status) {
                case 'INP'  : return 'REPP';break;
                case 'REP'  : return 'REPP';break;
                case 'EREP' : return 'ESC'; break;
                case 'EREREP': return 'EREP'; break;
                default	  	: return null;break;
            }
        }
        if($status == 'DEL'){
            switch ($user->status) {
                case 'REP'	 : return 'INP';break;
                case 'REPP'	 : return 'INP';break;
                case 'EREP'  : return 'ESC';break;
                case 'EREREP': return 'EREP';break;
                case 'INP'	 : return 'INP';break;
                default	  	: return null;break;
            }
        }
    }
}
