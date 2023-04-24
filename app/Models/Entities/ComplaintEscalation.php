<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\ComplaintSolution;
use App\Models\Entities\ComplaintUser;
use UserHelper;
use Log;

class ComplaintEscalation extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="complaint_escalations";
	protected $primaryKey = 'complaint_escalation_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'complaint_id_fk', 'owner_id_fk', 'escalation_index', 'escalated_to_fk', 'escalated_by_fk', 'remarks', 'status'
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
	public function complaintOwner(){
	    return $this->belongsTo('App\Models\Entities\ComplaintUser', 'owner_id_fk', 'complaint_user_id_pk'); // use to detect the owner of the compaint within the escalation flow.
	}
	public function escalatedTo(){
// 		return $this->belongsTo('App\Models\Entities\User', 'escalated_to_fk', 'user_id_pk');
		return $this->belongsTo('App\Models\Entities\ComplaintUser', 'escalated_to_fk', 'complaint_user_id_pk');// Change to complaintUser model complaint_users_id_pk
	}
	public function escalatedBy(){
// 	    return $this->belongsTo('App\Models\Entities\User', 'escalated_by_fk', 'user_id_pk');
		return $this->belongsTo('App\Models\Entities\ComplaintUser', 'escalated_by_fk', 'complaint_user_id_pk'); // Change to complaintUser model complaint_users_id_pk
	}
	
	public static function updateEscalatedStatus($id, $data){
		/**
		 * $id contain a Complaint table reference
		 * Get the current INP status and update the escaltion status.
		 * Get the INPROGRESS escalation related to current user(From Complaint User table using Logged details) in relavent complaint.
		 */
		switch($data['status']){
			case 'REJ': 
			    $esObject = parent::where('complaint_id_fk',$id)->where('escalated_to_fk', ComplaintUser::where('complaint_id_fk', $id)->where('user_id_fk', UserHelper::getLoggedUserId ())->first()->complaint_user_id_pk)->where('status','INP')->first();
				$esObject->fill(['status' => $data['status']])->save(); break;
			default	  : null;break;
		}
	}
	public static function updateStatus($id, $data, $mode='ALL') {
		/**
		 * $id contain a Escalation table reference.
		 * Update all the escalations related to that owner of the complaint only. Other owner escalations may stay as it is.
		 * call from complaintSolution
		 */
		switch($mode){
			case 'ALL': 
			    $escalation = parent::find ( $id );
// 			    parent::where('complaint_id_fk',$escalation->complaint_id_fk)->where('owner_id_fk', $escalation->owner_id_fk)->update(['status' => $data['status']]);break;
			    $ownerEscalations = parent::where('complaint_id_fk',$escalation->complaint_id_fk)->where('owner_id_fk', $escalation->owner_id_fk)->whereNotIn('status',['REJ'])->get();
			    foreach ($ownerEscalations as $escalation){
// 			        Log::info('Escalation updateStatus >>'.json_encode ($data));
// 			        Log::info('Escalation updateStatus escalation >>'.json_encode ($escalation));
// 			        Log::info('Escalation updateStatus id >>'.json_encode ($id));
			        if($data['status'] == 'INP' && (int)$escalation->complaint_escalation_id_pk != (int)$id)
			            $escalation->fill(['status' => 'PAS'])->save();
			        $escalation->fill(['status' => $data['status']])->save();
			    }
			    break;
			case 'ONE': parent::where('complaint_escalation_id_pk',$id)->update(['status' => $data['status']]);break;
			default	  : null;break;
		}
	}
	
	public function save(array $options = Array()) {
		$dirty = $this->getDirty ();
		parent::save ();
		/**
		 * Double check if this get fired in here -> isset ( $dirty ['deleted_at'] )
		 */
		Log::info('Escalation dirty >>'.json_encode ($dirty));
		if (isset ( $dirty ['status'] ) || isset ( $dirty ['deleted_at'] )) {
		    if($this->status == 'REJ'){
		        $this->updateEarlyEscalationsStatus($this->complaint_id_fk,$this->owner_id_fk,$this->escalated_by_fk,$this->status);
		    }
		    /**
		     * Update esclatated by user status.
		     */
		    $this->updateComplaintUserStatus($this->escalated_by_fk, $this->status);
		    /**
		     * Only run if no solution added for the complaint yet. Otherwise complaint will remain with existing Status. 
		     * check if all OWNERS escalated, then mark the complaint as ESCALATED.
		     * @var unknown $solutionsCount
		     */
		    $solutionsCount = ComplaintSolution::where ( 'complaint_id_fk', $this->complaint_id_fk )->whereNotIn('status',['PEN','NACP','DEL'])->count ();
		    if ($solutionsCount < 1){
		      $this->updateComplaintStatus($this->complaint_id_fk, $this->status, 
		          parent::whereHas('escalatedBy',function ($q){
		              $q->where('user_role','OWNER'); 
		          })->where('complaint_id_fk', $this->complaint_id_fk)->whereNotIn('status',['REJ'])->count());
		    }
			
		}
		if(isset($options['old_complaint_escalation_id_pk']))
			self::updateStatus($options['old_complaint_escalation_id_pk'], array('status' => $this->getOverridedEarlyEscalateStatus($this->status)), 'ONE');
		/**
		 * Notifications.
		 */
		if (isset ( $dirty ['status'] )){
		    if($this->status == 'INP')
		        Complaint::sendNotifications ( $this->complaint_id_fk, 'ESCALATE', $this->complaint_escalation_id_pk ,'ESCTO');
		    else if($this->status == 'REJ'){
		        Complaint::sendNotifications ( $this->complaint_id_fk, 'ESCALATE', $this->complaint_escalation_id_pk ,'ESCREJ');
		    }
		}
		
		return parent::save ();
	}
	
	/**
	 * Call Before the event get fired
	 */ 
	public static function boot() {
		parent::boot ();
		
		static::deleting ( function ($complaint) {
		} );
		
		static::saving ( function ($complaint) {
		} );
		
		static::updating ( function ($complaint) {
		} );
	}
	
	private function updateComplaintStatus($complaintId,$status,$count){
	    $compaintStatus = $this->getOverridedComplaintStatus($complaintId,$status,$count);
		if($compaintStatus)
	       return Complaint::updateStatus($complaintId,array('status' => $compaintStatus, 'escalationCount' => $count));
	}
	
	private function updateEarlyEscalationsStatus($complaintId,$ownerId,$escalatedtById,$status){
// 		$escalation = parent::where('complaint_id_fk',$complaintId)->whereIn('status', ['PAS'])->last();
        /**
         *  Current escalation escalated_by_fk mean last escalation escalated_to_fk if any exists.
         */
	    $escalation = parent::where('complaint_id_fk',$complaintId)->where('owner_id_fk',$ownerId)->where('escalated_to_fk',$escalatedtById)->first();
		if($escalation){
			$escalation->update([
				'status' => $this->getOverridedEarlyEscalateStatus($status)		
			]);
		}
	}
	
	/**
	 * Update complaint owner Status
	 */
	private function updateComplaintUserStatus($complaintUserId,$status){
	    $userStatus = self::getOverridedComplaintUserStatus($status);
	    if($userStatus)
	        ComplaintUser::updateStatus($complaintUserId,array('status' => $userStatus),'ONE');
	}
	
	
	private function getOverridedEarlyEscalateStatus($status){
		switch ($status) {
			case 'INP': return 'PAS';break; //done
			case 'PAS': return 'PAS';break; 
			case 'COM': return 'COM';break; //done
			/**
			 * Override status
			 */
			case 'DEL': return 'INP';break; // done
			case 'REJ': return 'INP';break; // done
			default	  : return null;break;
		}
	}
	/**
	 * Not us after made it multiple owner play
	 * @param unknown $complaintId
	 * @param unknown $status
	 * @return string|NULL
	 */
	private function getOverridedComplaintStatus($complaintId,$status,$count){
		switch ($status) {
			case 'INP': return 'ESC';break; //done
			case 'PAS': return 'ESC';break; //done
			case 'REP': return 'ESC';break; //done earlier COM
			/**
			 * Override status
			 */
// 			case 'DEL': $this->checkReturnStatus($complaintId);break; //done
// 			case 'REJ': $this->checkReturnStatus($complaintId);break; //done
			case 'DEL': return ($count > 0 ? 'ESC': 'INP'); break;
			case 'REJ': return ($count > 0 ? 'ESC': 'INP'); break;
			default	  : return null;break;
		}
	}
	
	private function getOverridedComplaintUserStatus($status){
	    switch ($status) {
	        case 'INP' : return 'ESC';break;
	        case 'PAS' : return 'ESC';break;
	        case 'COM' : return 'EREP';break;
	        /**
	         * Override status
	         * REJ status handle and Update ComplaintUser at the statusUpdate function in EscalateRepository
	         */
	        case 'REJ' : return 'INP';break; //done Earlier INP. now REJ
	        default	   : return null;break;
	    }
	}
	
	private function checkReturnStatus($complaintId){
		$escalations = parent::where('complaint_id_fk',$complaintId)->whereIn('status', ['INP','PAS'])->get();
		if(count($escalations) > 0){
			return 'ESC';
		}
		return 'INP';
	}
}
