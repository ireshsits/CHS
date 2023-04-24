<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Entities\ComplaintSolution;
use Log;
use Illuminate\Support\Facades\DB;

class ComplaintUser extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="complaint_users";
	protected $primaryKey = 'complaint_user_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'complaint_id_fk', 'user_id_fk', 'branch_department_id_fk', 'user_role', 'assigned_by_id_fk', 'primary_owner', 'system_role', 'role_index', 'reject_reason', 'status'
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
	public function user(){
		return $this->belongsTo('App\Models\Entities\User', 'user_id_fk', 'user_id_pk' );
	}
	public function allUser(){
		return $this->belongsTo('App\Models\Entities\User', 'user_id_fk', 'user_id_pk' )->withTrashed();
	}
	public function department() {
		return $this->belongsTo ( 'App\Models\Entities\BranchDepartment', 'branch_department_id_fk', 'branch_department_id_pk');
	}
	public function assignedBy(){
	    return $this->belongTo('App\Models\Entities\User', 'assigned_by_id_fk', 'user_id_pk');
	}
	public function solutions(){
	    return $this->hasMany('App\Models\Entities\ComplaintSolution', 'resolved_by_fk', 'complaint_user_id_pk');
	}
	public function escalatedTo(){
	    return $this->hasMany('App\Models\Entities\ComplaintEscalation', 'escalated_to_fk', 'complaint_user_id_pk');
	}
	public function escalatedBy(){
	    return $this->hasMany('App\Models\Entities\ComplaintEscalation', 'escalated_by_fk', 'complaint_user_id_pk');
	}
	
	public static function updateStatus($id, $data, $mode='ALL') {
// 	    Log::info('Updating Complaint ID >>'. json_encode ($id));
// 	    Log::info('Updating Complaint User >>'. json_encode ($data));
	    /**
	     * $id contain a Escalation table reference.
	     * Update all the escalations related to that complaint.
	     * call from complaintSolution
	     */
	    if($data['status'] == 'INP')
	        $data['reject_reason'] = null;

	    if($data['status'] == 'INP') {
			$users = parent::where('complaint_id_fk', $id)->whereIn('user_role', $data['roles'])->get();
			foreach ($users as $user) {
				$complaintSolution = ComplaintSolution::where('complaint_id_fk', $id)->where('owner_id_fk', $user->complaint_user_id_pk)->first();
				if($complaintSolution != null) {
					$affected = DB::table('complaint_solutions')->where('complaint_id_fk', $id)->where('owner_id_fk', $user->complaint_user_id_pk)->update(['status' => 'NACP']);
					// $complaintSolution->update(['status' => 'NACP']);
				}
			}
	    }

	    Log::info('Complaint User updatestatus >>'.json_encode ($data));
	    switch($mode){
	        case 'ALL-SOLVED': parent::WhereHas('solutions', function($q){ $q->where('status','ACP'); })->where('complaint_id_fk', $id)->update(['status' => $data['status']]);break; // not using
	        case 'ALL': 
	            //parent::where('complaint_id_fk', $id)->whereIn('user_role', $data['roles'])->update(['status' => $data['status']]);break;
	            $users = parent::where('complaint_id_fk', $id)->whereIn('user_role', $data['roles'])->get();
	            foreach ($users as $user){
	                $user->fill($data)->save();
	            }
	            ;break;
	        case 'ONE': 
	            //parent::where('complaint_user_id_pk',$id)->update(['status' => $data['status']]);break;
	            $user = parent::where('complaint_user_id_pk',$id)->first();
	            $user->fill($data)->save();
	            break;
	        default	  : null;break;
	    }
	}
	
	public function save(array $options = Array()) {
	    $dirty = $this->getDirty ();
	    parent::save ();
	    /**
		/**
		 * Double check if this get fired in here -> isset ( $dirty ['deleted_at'] )
	     * Comlplaint owner will only get deleted at pending state. So no need to worry
	     * Complaint owner will get REJ.
	     * Complaint escalator will REJ or deleted. but not affected on complaint.
	     * But escalated user may get deleted @ inprogres/escalated state.
	     * mode contain the user role. role pass when OWNER do REJECTION
	     * works with REJ flow.
	     */
	    Log::info('Complaint User dirty >>'.json_encode ($dirty));
	    if (isset ( $dirty ['status'] ) || isset ( $dirty ['deleted_at'] )) {
	        if(isset($options['role']) && $options['role'] =='OWNER'){
	            /**
	             * Check any solutions exists. if then pass the solution count with COM status to trigger update function.
	             * @var unknown $activeCount
	             */
	            $solutionCount = ComplaintSolution::where ( 'complaint_id_fk', $this->complaint_id_fk )->whereIn('status',['ACP','VFD'])->count ();
	            if($solutionCount > 0)
	                return $this->updateComplaintStatusUponSolutions($this->complaint_id_fk, $this->status, $solutionCount);
	            else{
	                /**
	                 * If no ACP/VFD solution, need to check wether if its have any active users in progress.
	                 * will trigger only if no any owner without ACT/REJ status.
	                 */
	                $activeCount = parent::where ( 'complaint_id_fk', $options['id'] )->where('user_role',$options['role'])->whereNotIn('status', ['ACT', 'REJ'])->count ();
	                if (! $activeCount > 0)
	                    return $this->updateComplaintStatus($this->complaint_id_fk, $this->status, 0);
	            }
	        }
	    }
	    return parent::save ();
	}
	
	/**
	 * Call Before the event get fired
	 */
	public static function boot()
    {
        parent::boot();

        static::deleting(function ($complaintUser) {
            /**
             * Need to handle complaint escalator delete event
             */
            if($complaintUser->user_role == 'ESCAL'){
                
            }
        });

        static::saving(function ($complaintUser) {});

        static::updating(function($complaintUser){});
	}
	
	private function updateComplaintStatusUponSolutions($complaintId,$status,$count){
	    $compaintStatus = $this->getOverridedComplaintStatusUponSolutions($status);
	    return Complaint::updateStatus($complaintId,array('status' => $compaintStatus, 'solutionCount' => $count));
	}
	/**
	 * Update complaint status according to reply
	 * @param unknown $complaintId
	 * @param unknown $status
	 * @return unknown
	 */
	private function updateComplaintStatus($complaintId,$status,$count){
	    $compaintStatus = $this->getOverridedComplaintStatus($status);
	    return Complaint::updateStatus($complaintId,array('status' => $compaintStatus, 'activeCount' => $count));
	}
	
	private function getOverridedComplaintStatusUponSolutions($status){
	    switch ($status) {	        
	       case 'ACT'	   : return 'COM';break;
	       case 'INP'	   : return 'COM';break;
	       case 'ESC'      : return 'COM';break;
	       case 'REP'      : return 'COM';break;
	       case 'EREP'     : return 'COM';break;
	       case 'EREREP'   : return 'COM';break;
	       case 'REPTRNFR' : return 'COM';break;
	       case 'EREPTRNFR': return 'COM';break;
	       case 'REJ'      : return 'COM';break;
	    }
	}
	
	private function getOverridedComplaintStatus($status){
	    switch ($status) {
	        case 'ACT'	    : return 'REJ';break;
	        case 'INP'	    : return 'REJ';break;
	        case 'ESC'      : return 'REJ';break;
	        case 'REP'      : return 'REJ';break;
	        case 'EREP'     : return 'REJ';break;
	        case 'EREREP'   : return 'REJ';break;
	        case 'REPTRNFR' : return 'REJ';break;
	        case 'EREPTRNFR': return 'REJ';break;
	        case 'REJ'      : return 'REJ';break;
	        /**
	         * Override status
	         */
	        case 'DEL'	 : return 'INP';break; //done
	        default	   	 : return null;break;
	    }
	}
}
