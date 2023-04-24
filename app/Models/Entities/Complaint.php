<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Entities\BaseModel;
use DateHelper;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use App\Jobs\NotificationJob;
use Log;
use App\Jobs\CacheKeyGenerateJob;
use App\Models\Entities\ComplaintMode;
use App\Models\Entities\ComplaintUser;
use App\Models\Entities\ComplaintSolution;
use App\Models\Entities\ComplaintReopen;
use App\Models\Entities\ComplaintClose;
use EnumTextHelper;
use App\Traits\CustomConfigurationService;

class Complaint extends Model
{
	use LogsActivity, SoftDeletes, CascadeSoftDeletes;
	use CustomConfigurationService;
    //
// 	protected $connection = 'oracle';
	protected $table="complaints";
	protected $primaryKey = 'complaint_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	    'reference_number', 
// 		'branch_department_id_fk', 
		'area_id_fk','category_id_fk', 
// 	    'sub_category_id_fk', 
        'complainant_id_fk',
// 		'complaint_recepient_id_fk',
		'complaint_mode_id_fk',
// 	    'reject_reason',
        'account_no', 'complaint', 'priority_level', 'type',
// 		'owner_role', 
		'open_date', 'close_date', 'status'
	];
	
	/**
	 * Logged the activities related tot he given fields
	 * @var array
	 */
	protected static $logAttributes = ['reference_number','complaint', 'status'];
	
	/**
	 * given child rows will be deleted
	 */
	protected $cascadeDeletes = ['complaintUsers','solutions', 'reminders', 'escalations', 'attachments', 'complaintReopens'];
	
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
			'deleted_at'
	];
	
// 	public function department(){
// 		return $this->belongsTo('App\Models\Entities\BranchDepartment', 'branch_department_id_fk', 'branch_department_id_pk');
// 	}

	public function area(){
	    return $this->belongsTo('App\Models\Entities\Area', 'area_id_fk', 'area_id_pk');
	}
	
	public function category(){
		return $this->belongsTo('App\Models\Entities\Category', 'category_id_fk', 'category_id_pk');
	}
	/**
	 * Removed with CR3
	 */
// 	public function subCategory(){
// 		return $this->belongsTo('App\Models\Entities\SubCategory', 'sub_category_id_fk', 'sub_category_id_pk');
// 	}
	
	public function complainant(){
		return $this->belongsTo('App\Models\Entities\Complainant', 'complainant_id_fk', 'complainant_id_pk');
	}
	
	public function solutions(){
		return $this->hasMany('App\Models\Entities\ComplaintSolution', 'complaint_id_fk', 'complaint_id_pk');
	}
	
	public function reminders(){
		return $this->hasMany('App\Models\Entities\ComplaintReminder', 'complaint_id_fk', 'complaint_id_pk');
	}
	
	public function escalations(){
		return $this->hasMany('App\Models\Entities\ComplaintEscalation', 'complaint_id_fk', 'complaint_id_pk');
	}
	
	public function attachments(){
		return $this->hasMany('App\Models\Entities\ComplaintAttachment', 'complaint_id_fk', 'complaint_id_pk');
	}
// 	public function complaintRecepient(){
// 		return $this->belongsTo('App\Models\Entities\User', 'complaint_recepient_id_fk' , 'user_id_pk');
// 	}
	/**
	 * Added with CR2 
	 */
	public function complaintUsers(){
		return $this->hasMany('App\Models\Entities\ComplaintUser', 'complaint_id_fk', 'complaint_id_pk');
	}
	public function complaintMode(){
		return $this->belongsTo('App\Models\Entities\ComplaintMode', 'complaint_mode_id_fk' , 'complaint_mode_id_pk');
	}	
	public function complaintReopens(){
	    return $this->hasMany('App\Models\Entities\ComplaintReopen', 'complaint_id_fk', 'complaint_id_pk');
	}
	public function complaintCloses(){
	    return $this->hasMany('App\Models\Entities\ComplaintClose', 'complaint_id_fk', 'complaint_id_pk');
	}
	public function complaintNotificationOtherUsers(){
		return $this->hasMany('App\Models\Entities\ComplaintNotificationOtherUser', 'complaint_id_fk', 'complaint_id_pk');
	}
	
	public static function updateStatus($id, $data) {
	    Log::info('Updating Complaint updatestatus >>'. json_encode ($data));
		$complaint = parent::find ( $id );
		/**
		 * If the number of solutions is equal or greater than number of owners then the solution mark as replied to view by th recepient and cc admin. 
		 * cc admin can mark it as completed.
		 */
		$ownerCount = ComplaintUser::where('complaint_id_fk', $id)->where('user_role', 'OWNER')->whereNotIn('status', ['ACT','REJ'])->count();
		if(isset($data['solutionCount'])){
// 		    Log::info('Updating Complaint params with solutionCount -> ownerCount >>'. json_encode ($ownerCount));
		    if((int)$data['solutionCount'] >= $ownerCount){
    		    $data['status'] = 'COM';
		    }else if((int)$data['solutionCount'] >= 1 && (int)$data['solutionCount'] < $ownerCount){
		        $data['status'] = 'REP';
		    }
		}
		/**
		 * Not get fired if has any solution(s) added.
		 * if all the OWNERS done escalate complaint will mark as escalated.
		 * if ESCALATED count is greater or equal to OWNERS will allow to change the status.
		 */
// 		$ownerCount = ComplaintUser::where('complaint_id_fk', $id)->where('user_role', 'OWNER')->whereIn('status', ['ESC','EREP', 'EREREP'])->count();
		if(isset($data['escalationCount'])){
// 		    Log::info('Updating Complaint params with escalationCount -> ownerCount >>'. json_encode ($ownerCount));
		    if((int)$data['escalationCount'] < $ownerCount){
// 		        return true;
		        if($complaint->status == 'ESC'){
		            $data['status'] = 'INP';
		        }else{
		            return true;
		        }
		    }
		}
		
		return $complaint->fill($data)->save();
	}
	
	public static function getLastReferenceNumber($params) {
		$modeCode = ComplaintMode::getModeCode($params['complaint_mode_id_fk']);
		/**
		 * Use the db created_at column as the system cannot use separate date as complaint date.
		 */
		$count = parent::withTrashed()->whereYear('created_at', DateHelper::getYear())->whereMonth('created_at',DateHelper::getMonth())->count();
		$rfn = $modeCode.'_'.DateHelper::getReferenceFormatDate();
		do {
			$count++;
		}while(parent::where('reference_number', $rfn.'-'.sprintf ( "%04d", $count))->exists());
		return $rfn.'-'.sprintf ( "%04d", $count);
	}

	// format reference number for new mode - CR4
	public static function formatReferenceNumberForMode($refNo, $params) {

		$modeCode = ComplaintMode::getModeCode($params['complaint_mode_id_fk']);
		
		return $rfn = $modeCode.'_'.trim(substr($refNo, strpos($refNo, '_') + 1));

	}

	/**
	 *
	 * @param array $options        	
	 * @return unknown
	 */
	public function save(array $options = Array()) {
		$dirty = $this->getDirty ();
		
		Log::info('Complaint dirty >>'.json_encode ($dirty));
		$originalStatus = $this->getOriginal ( 'status' );
		parent::save ();

		// check for report purpose complaint
		$isReportingComplaint = $this->getReportPurposeComplaintStatus(['complaint_id' => $this->complaint_id_pk]);

		if (isset ( $dirty ['status'] )) {
		    /**
		     * Initially status will be saved as PEN. No nitifications.
		     * When forward the complaint status will change to INP.
		     */
			if ($dirty ['status'] == 'INP') {

				/**
				 * Continue normal process if not a report purpose complaint
				 */
				if (!$isReportingComplaint) {

					/**
					 * Update close_date to null.
					 */
					$this->close_date = null;
					/**
					 * Update all the complaint users to INP
					 */
					if ($originalStatus == 'PEN' || $originalStatus == 'REJ')
						ComplaintUser::updateStatus($this->complaint_id_pk, array('status' => 'INP', 'roles' => ['OWNER']), 'ALL');
					
					if ($originalStatus == 'CLO' || $originalStatus == 'COM'){
	// 				    ComplaintUser::updateStatus($this->complaint_id_pk, array('status' => 'INP', 'roles' => ['OWNER']), 'ALL-SOLVED');
						ComplaintSolution::updateStatus($this->complaint_id_pk, array('status' => 'NACP'));
						if($originalStatus == 'CLO'){
							ComplaintClose::updateStatus($this->complaint_id_pk, array('status' => 'REJ'));
						}
					}
					
					/**
					 * CCC users are capable of reopening (INP) a completed (COM/REP) complaint.
					 * All the users involved in the complaint will be notified.
					 * Currently logged in user will ignored.
					 */
					if (is_null ( $originalStatus ) || $originalStatus == 'PEN')
						self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1);
					else if ($originalStatus == 'CLO')
						self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1, 'REOPN' );
					else if ($originalStatus == 'REJ')
						self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1, 'REFWD' );

				} else {
					/**
					 * For report purpose complaints
					 */
					// mark completed & add completed by IT to solutions
				}
				
			}
			if ($dirty ['status'] == 'ESC') {
				/**
				 * Escalated person with Inprogress (INP) status will be notified.
				 * ESCALATED_ALL for notify Inprogress (INP) and Pass (PAS) status eascalated personnal.
				 * Currently logged in user will ignored.
				 */
				if ($originalStatus == 'INP')
// 					self::sendNotifications ( $this->complaint_id_pk, 'ESCALATED' );
				    self::sendNotifications ( $this->complaint_id_pk, 'RECEPIENT', -1 );
			}
			if ($dirty['status'] == 'COM') {
			    /**
			     * If Complaint again get mark as COM/CLO need to check or INPROGRESS reopens and change the status to COMPLETED
			     */
			    $this->updateComplaintReopens($this->complaint_id_pk, $this->status);
			    self::sendNotifications ( $this->complaint_id_pk, 'RECPT-CCC', -1 );
			}
			if ($dirty ['status'] == 'CLO') {
				/**
				 * Update close_date to current date.
				 */
				$this->close_date = DateHelper::getDate ();
				/**
				 * If Complaint again get mark as REP/COM need to check or INPROGRESS reopens and change the status to COMPLETED
				 */
				$this->updateComplaintReopens($this->complaint_id_pk, $this->status);
				/**
				 * CCC users are capable of reopening a completed complaint.
				 * All the users involved in the complaint will be notified.
				 * Currently logged in user will ignored.
				 */
// 				if ($originalStatus == 'INP' || $originalStatus == 'ESC')
                if($originalStatus == 'COM')
					self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1 );
			}
			if ($dirty ['status'] == 'REJ') {
				/**
				 * All the users involved in the complaint will be notified.
				 */
				self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1 );
			}
		} else {
			/**
			 * Closed (CMP) complaint may re-replied.
			 * All the users involved in the complaint will be notified.
			 * Not gt called
			 */
			if($this->status == 'CLO' && $originalStatus == 'CLO')
				self::sendNotifications ( $this->complaint_id_pk, 'ALL', -1 ,'REREP');
		}
		return parent::save ();
	}
	
	/**
	 * Call Before the event get fired
	 */
	public static function boot() {
		parent::boot();
		static::deleting(function($complaint){
			CacheKeyGenerateJob::dispatch();
		});
		static::saving(function($complaint){
			CacheKeyGenerateJob::dispatch();
		});
		static::updating(function($complaint){
			CacheKeyGenerateJob::dispatch();
		});
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
	
	/**
	 * Fetch Enum values
	 */
	public static function getPossibleEnumValues($name){
		switch($name){
			case 'priority_level': return self::returnPriorityEnums();
		}
	}
	
	public function updateComplaintReopens($complaintId,$status){
	    $openStatus = $this->getOverridedReopenStatus($status);
	    return ComplaintReopen::updateStatus($complaintId,array('status' => $openStatus));
	}
	
	private function getOverridedReopenStatus($status){
	    switch ($status) {
	        case 'COM'	: return 'COM';break;
	        case 'CLO'	: return 'COM';break;
	        default	  	: return null;break;
	    }
	}
	
	private static function returnPriorityEnums(){
		$levels = ['CRT','IMP','NOR','LOW'];
		foreach($levels as $level){
			$enum[] = array('id' => $level, 'text' => EnumTextHelper::getEnumText($level));
		}
		return $enum;
	}
}
