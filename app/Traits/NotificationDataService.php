<?php

namespace App\Traits;

use App\Models\Repositories\Complaints\ManageComplaintsRepository;
use Log;
use RoleHelper;
use UserHelper;
use App\Classes\Ldap\Quries\Fetch;
use App;
use Cache;
use App\Models\Entities\User;


trait NotificationDataService
{
    /**
     * FYR
     * resolved_by_fk, owner_id_fk, escalated_by_fk, escalated_to_fk all are refers to ComplaintUser table. Not directly to Users table
     * amendment_by_fk, closed_by_id_fk refers the users table
     */
	public function setNotificationData($params){
		$key = $this->generateCacheKey ($params);
		$this->fetchFromDB($key, $params);
	}
	/**
	 * Webpush /pusher
	 * @param unknown $params
	 * @return unknown
	 */
	public function getWebPushNotificationData($params){
		$key = $this->generateCacheKey ($params);
		if (Cache::has ( $key )) {
			Log::info('Fetch data from Cache >>');
			$data = $this->getFromCache ( $key );
		}else{
		    $data = $this->fetchFromDB($key, $params);
		}
		return $data;
	}
	/**
	 * Email
	 * @param unknown $params
	 * @return unknown
	 */
	public function getEmailNotificationData($params){ 
		$key = $this->generateCacheKey ($params);
		if (Cache::has ( $key )) {
			Log::info('Fetch data from Cache >>');
			$data = $this->getFromCache ( $key );
		}else{
		    $data = $this->fetchFromDB($key, $params);
		}
		/**
		 * Get complaint as a eloquent collection
		 */
// 		$data['complaint'] = $complaint;
		return $data;
	}
	private function fetchFromDB($key, $params){
	    Log::info('Fetch data from DB response >>'. json_encode($params));
	    $complaint = $this->getComplaint( $params ['complainId']);
	    /**
	     * Get complaint as a eloquent collection
	     */
	    $data['complaint'] = $complaint;
	    
	    $data['notifyEmails'] = array_values(array_unique($this->getComplaintNotifyEmails($params, $complaint)));

		// Check for other complaint notification users (other user notification events - Inprogress and Closed)
		$othernotificationusers = array_values(array_unique($this->getAllOtherNotificationUserEmails($params, $emails = [], $complaint)));
		
	    /**
	     * Currently only cc to CCC admin. Not send email as a cc to admin when complaint Completed(RECT-CCC add admin to mail->to array).
	     * Only cc if any notify email is present 
	     */
	    if(($params ['notifyRole'] !== 'RECT-CCC' && count($data['notifyEmails']) > 0) || count($othernotificationusers) > 0){
	        $data['ccEmails'] = $this->getCCCEmails($params,$data['notifyEmails'], $complaint, ['ACT'], $mode='SEPARATE');
	    }
	    $data['recipientDetails'] = $this->getRecipient($complaint);
	    $data['resolvedByDetails'] = $this->getResolvedDetails($params,$complaint);
	    $data['closedByDetails'] = $this->getClosedDetails($params,$complaint);
	    $data['reopenedDetails'] = $this->getReopenedDetails($params,$complaint);
	    $data['complaintArray'] = $complaint->toArray();
	    $data['url'] = $this->getUrl($params);
	    /**
	     * Put in Cache
	     */
	    $this->putInCache ( $key, $data, 'Notification_data' );
	    return $data;
	}
	private function getUrl($params) {
		return route ( 'dashboard.complaint.setup').'/'.urlencode('mode=edit&id='.$params ['complainId']);
	}
	private function putInCache($key, $content, $tag) {
		Log::info('Put in Cache >>'.json_encode($key));
		Cache::put ( $key, json_encode ( $content ), 600 ); //10 minutes
	}
	private function getFromCache($key) {
		return (array) json_decode ( Cache::get ( $key ) );
	}
	private function generateCacheKey($params) {
	    return ('Email-'.$params['action']??'').'-'.($params['complainId']??'').'-'.($params['notifyRole']??'').($params['subModuleId']?'-'.$params['subModuleId']:'').($params['schedule']?'-'.$params['schedule']:'').($params['override_status']?'-'.$params['override_status']:'');
	}
	private function getComplaint($id) {
		$repo = new ManageComplaintsRepository ();
		return $repo->getComplaint ( array (
				'complaint_id_pk' => $id
		) );
	}
	private function getSolution($id) {
	    $repo = new ManageComplaintsRepository ();
	    return $repo->getSolution ( array (
	        'complaint_solution_id_pk' => $id,
	        'with_trashed' => true
	    ) );
	}
	private function getAmendment($id) {
	    $repo = new ManageComplaintsRepository ();
	    return $repo->getAmendment ( array (
	        'solution_amendment_id_pk' => $id,
	        'with_trashed' => true
	    ) );
	}
	private function getRecipient($complaint){
	    $data = array();
	    foreach ($complaint->complaintUsers as $user){
	        if($user->user_role == 'RECPT'){
	            $data['name'] = $user->user->first_name.' '.$user->user->last_name;
	            $data['department'] = $user->department->name;
	        }
	    }
	    return $data;
	}
	private function getResolvedDetails($params, $complaint){
	    $data = [];
	    if(isset($params['subModuleId']) && (int)$params['subModuleId'] < 0 && $params ['notifyRole'] == 'SOLUTION'){
	        foreach ($complaint->solutions as $solution) {
	            if ($solution->complaint_solution_id_pk == $params['subModuleId']) {
	                $data[] = $solution->resolvedBy->user->first_name.' '.$solution->resolvedBy->user->last_name.' ('.$solution->resolvedBy->department->name.')';
	                break;
	            }
	        }
	    }else{
	        foreach ($complaint->solutions as $solution){
	            // $data[] = $solution->resolvedBy->user->first_name.' '.$solution->resolvedBy->user->last_name.' ('.$solution->resolvedBy->department->name.')';
				$data[] = $solution->resolvedByUser->user->first_name.' '.$solution->resolvedByUser->user->last_name.' ('.$solution->resolvedByUser->department->name.')';
	        }
	    }
	    return $data;
	}
	private function getClosedDetails($params, $complaint){
	    $data = [];
	    foreach ($complaint->complaintCloses as $close){
	        if($close->status == 'INP')
	            $data[] = $close->closedBy->first_name.' '. $close->closedBy->last_name;
	    }
	    return $data;
	}
	private function getReopenedDetails($params, $complaint){
	    $data = [];
	    foreach ($complaint->complaintReopens as $reopen){
	        if($reopen->status == 'INP')
	            $data[] = $reopen->reopnedBy->first_name.' '. $reopen->reopnedBy->last_name;
	    }
	    return $data;
	}
	private function getComplaintNotifyEmails($params, $complaint = []) {
		if ($complaint) {
			$emails = [];
			switch ($params ['notifyRole']) {
				case 'ALL' :
// 					$emails = $this->getEscalatedToUserEmails ($params,$emails, $complaint ,[ 'INP', 'PAS' ]);
// 					$emails = $this->getEscalatedByUserEmails ($params,$emails, $complaint ,[ 'PAS' , 'CLO' ]);
// 					$emails = $this->getOwnerEmails ($params,$emails, $complaint );
// 					$emails = $this->getRecepientEmails ($params,$emails, $complaint);
// 					return $emails;
                    $emails = $this->getAllUserEmails ($params, $emails, $complaint, ['REJ']);
                    return $emails;
					break;
				case 'ESCALATED_ALL' :
					$emails = $this->getEscalatedAllUserEmails ($params,$emails, $complaint, [ 'INP', 'PAS' ]);
					return $emails;
					break;
// 				case 'ESCALATED' :
// 					$emails = $this->getEscalatedToUserEmails ($params,$emails, $complaint, [ 'INP']);
// 					return $emails;
// 					break;
				case 'ESCALATE' :
				    $emails = $this->getEscalateUserEmails ($params,$emails, $complaint);
				    return $emails;
				    break;
				case 'SOLUTION' :
				    $emails = $this->getSolutionUserEmails ($params,$emails, $complaint);
				    return $emails;
				    break;
				case 'AMENDMENT' :
				    $emails = $this->getAmendmentUserEmails ($params,$emails, $complaint);
				    return $emails;
				    break;
				case 'RECEPIENT' :
				    $emails = $this->getRecipientEmail($params,$emails, $complaint);
				    return $emails;
				    break;
				case 'ALL-CCC' : // Currently not called from anywhere.
				    $emails = $this->getAllUserEmails($params,$emails, $complaint, ['ACT']);
				    $emails = $this->getCCCEmails($params,$emails, $complaint, ['ACT']);
				    return $emails;
				    break;
				case 'RECPT-CCC' :
				    $emails = $this->getRecipientEmail($params,$emails, $complaint);
				    $emails = $this->getCCCEmails($params,$emails, $complaint, ['ACT']);
				    return $emails;
				    break;
				default : return [];break;
			}
		} else {
			/**
			 * Not in the current scope. all the users related to branches with roles will be fetch in to local DB.
			 * Fetch emails from ADLDAP
			 * @var Ambiguous $repo
			 */
			$repo = new Fetch ();
			return $repo->getComplaintOwnerEmails ( array (
					'id' => $params['complainId']
			) );
		}
	}
	private function getAllUserEmails($params, $emails = [], $complaint, $status_array){
	    foreach ($complaint->complaintUsers as $user){
	        if(! UserHelper::checkUserIsLoggedIn ($user->user , $params) &&  !in_array ( $user->status, $status_array ))
	           $emails[] = $user->user->email;
	    }
	    return $emails;
	}
	private function getRecipientEmail($params,$emails = [], $complaint){
	    foreach ($complaint->complaintUsers as $user){
	        if($user->user_role == 'RECPT'){
	            $emails[] = $user->user->email;
	        }
	    }
	    return $emails;
	}
	private function getEscalatedAllUserEmails($params,$emails = [], $complaint, $status_array){
        /**
         * Currently get called only by Reminder option.
         * subModuleId contains the user_id_pk.
         */
        if ($params['subModuleId'] == 0) {
        /**
         * Reminder send by system.
         * No need of now as system reminders still call with 'ALL'.
         */
        } else if (in_array($params['subModuleId'], $complaint->userRoles)) {
            /**
             * User is a complaintUser table user
             * User should have a pending escalation.
             */

            foreach ($complaint->escalations as $escalate) {
                if ($complaint->userRoles[$params['subModuleId']] == 'OWNER') {
                    if ($escalate->complaintOwner->user->user_id_pk == $params['subModuleId'] && in_array($escalate->status, $status_array) && ! UserHelper::checkUserIsLoggedIn ($escalate->escalatedTo->user , $params)) {
                        $emails[] = $escalate->escalatedTo->user->email;
                    }
                } else if ($complaint->userRoles[$params['subModuleId']] == 'ESCAL') {
                    if ($escalate->escalatedBy->user->user_id_pk == $params['subModuleId'] && in_array($escalate->status, $status_array) && ! UserHelper::checkUserIsLoggedIn ($escalate->escalatedTo->user , $params)) {
                        $emails[] = $escalate->escalatedTo->user->email;
                    }
                }
            }
        } else {
        /**
         * Remindered by CCC
         * No need of now as system reminders still call with 'ALL'.
         */
        }

        return $emails;
	}
	private function getEscalateUserEmails($params, $emails = [], $complaint){
        if (count($complaint->escalations) > 0) {
            foreach ($complaint->escalations as $escalate) {
                if ($escalate->complaint_escalation_id_pk == $params['subModuleId']) {
                    if ($params['override_status'] == 'ESCTO')
                        $emails[] = $escalate->escalatedTo->user->email;
                    else if ($params['override_status'] == 'ESCREJ') {
                        /**
                         * If Escalator reject the escalation
                         */
                        $emails[] = $escalate->escalatedBy->user->email;
                        $emails[] = $escalate->complaintOwner->user->email;
                    }
                }
            }
        }
        return $emails;   
	}
	private function getSolutionUserEmails($params, $emails = [], $complaint){
        if (count($complaint->solutions) > 0) {
            foreach ($complaint->solutions as $solution) {
                if ($solution->complaint_solution_id_pk == $params['subModuleId']) {
//                     Log::info('Solution >>'. json_encode($solution));
                    /**
                     * Verifiy do by the resolver.
                     * resolver can be either owner/escalator. inform owner regarding the solution.
                     */
                    if ($params['override_status'] == 'VFD' && $solution->resolved_by_fk !== $solution->owner_id_fk && ! UserHelper::checkUserIsLoggedIn($solution->complaintOwner->user, $params)) {
                        $emails[] = $solution->complaintOwner->user->email;
                    }
                    /**
                     * Accepted by owner if the resolver is a escalator.
                     * inform escalator regarding the Accepting.
                     */
                    if ($params['override_status'] == 'ACP' && $solution->resolved_by_fk !== $solution->owner_id_fk && ! UserHelper::checkUserIsLoggedIn($solution->resolvedBy->user, $params)) {
                        $emails[] = $solution->resolvedBy->user->email;
                    }
                    /**
                     * Not Accepted by either Owner/ CCC user .
                     * So cannot add constraint resolved_by_fk !== owner_id_fk
                     */
                    if ($params['override_status'] == 'NACP' && ! UserHelper::checkUserIsLoggedIn($solution->resolvedBy->user, $params)) {
                        $emails[] = $solution->resolvedBy->user->email;
                    }
                    break;
                }
            }
        }
        /**
         * Solution only can be deleted by the same person added the solution
         */
        if ($params['override_status'] == 'DEL') {
            $solution = $this->getSolution($params['subModuleId']);
            if ($solution->resolved_by_fk !== $solution->owner_id_fk && ! UserHelper::checkUserIsLoggedIn($solution->complaintOwner->user, $params)) {
                $emails[] = $solution->complaintOwner->user->email;
            }
        }
        /**
         * Append Recipeint for every solution status update
         */
        $emails = $this->getRecipientEmail($params, $emails, $complaint);

        return $emails; 
	}
	private function getAmendmentUserEmails($params,$emails, $complaint){
        if (count($complaint->solutions) > 0) {
            foreach ($complaint->solutions as $solution) {
                if (count($solution->amendments) > 0) {
                    foreach ($complaint->amendments as $amendment) {
                        if ($amendment->solution_amendment_id_pk == $params['subModuleId']) {
                            if ($params['override_status'] == 'VFD' && $solution->resolvedBy->user_id_fk !== $amendment->amendment_by_fk && ! UserHelper::checkUserIsLoggedIn($solution->resolvedBy->user, $params)) {
                                $emails[] = $solution->resolvedBy->user->email;
                            }
                            break;
                        }
                    }
                }
            }
        }
        /**
         * Solution only can be deleted by the same person added the solution
         */
        if ($params['override_status'] == 'DEL') {
            $amendment = $this->getAmendment($params['subModuleId']);
            if ($amendment->solution->resolvedBy->user_id_fk !== $amendment->amendment_by_fk && ! UserHelper::checkUserIsLoggedIn($amendment->solution->resolvedBy->user, $params)) {
                $emails[] = $amendment->solution->resolvedBy->user->email;
            }
        }
        /**
         * Append Recipeint for every solution status update
         */
        $emails[] = $this->getRecipientEmail($params, $emails = [], $complaint);

        return $emails;
	}
	private function getAllOtherNotificationUserEmails($params, $emails = [], $complaint){
	    foreach ($complaint->complaintNotificationOtherUsers as $user){
	        if(! UserHelper::checkUserIsLoggedIn ($user->user , $params))
	           $emails[] = $user->user->email;
	    }
	    return $emails;
	}

	private function getCCCEmails($params,$emails, $complaint, $status_array, $mode='APPEND'){

		// Complaint specific other user notification cc emails if only status => INP or CLO
		if ($complaint->status == 'INP' || $complaint->status == 'CLO') {
			// Complaint specific other user notification cc emails
			if ($complaint->complaintNotificationOtherUsers != null) {
				foreach ($complaint->complaintNotificationOtherUsers as $user) {
					if(!in_array($user->user->email, $emails) && ! UserHelper::checkUserIsLoggedIn ($user, $params)){
						$ccEmails[] = $user->user->email;
					}
				}
			}
		}

	   $cccadmins = explode('|', RoleHelper::getCCCRoles('RAW_NAMES'));
	   $users = User::whereHas('roles', function ($q) use($cccadmins) {
            $q->whereIn('name', $cccadmins);
        })->get();
        foreach ($users as $user) {
            if(!in_array($user->email,$emails) && ! UserHelper::checkUserIsLoggedIn ($user, $params)){
                if($mode == 'SEPARATE')
                    $ccEmails[] = $user->email;
                else if($mode == 'APPEND')
                    $emails[] = $user->email;
            }
        }
        if($mode == 'SEPARATE')
            return $ccEmails??[];
        else
            return $emails;
	}
// 	private function getEscalatedToUserEmails($params,$emails = [], $complaint, $status_array){
// 		if (count ( $complaint->escalations ) > 0) {
// 			foreach ( $complaint->escalations as $escalate ) {
// 				if (in_array ( $escalate->status, $status_array ) && $escalate->escalatedTo->email && ! UserHelper::checkUserIsLoggedIn ($escalate->escalatedTo , $params))
// 					$emails [] = $escalate->escalatedTo->email;
// 			}
// 		}
// 		return $emails;
// 	}
// 	private function getEscalatedByUserEmails($params, $emails = [], $complaint, $status_array){
// 		if (count ( $complaint->escalations ) > 0) {
// 			foreach ( $complaint->escalations as $escalate ) {
// 				if (in_array ( $escalate->status, $status_array ) && $escalate->escalatedBy->email && ! UserHelper::checkUserIsLoggedIn ($escalate->escalatedBy , $params))
// 					$emails [] = $escalate->escalatedBy->email;
// 			}
// 		}
// 		return $emails;
// 	}
// 	private function getOwnerEmails($params, $emails = [], $complaint){
// 		$ownerRoles = RoleHelper::getOwnerRoles ();
// 		foreach ( $complaint->department->users as $user ) {
// 			if ($user->user->email) {
// 				if ($complaint->owner_role == 'ALL') {
// 					if (UserHelper::checkUserHasAnyRole ( $ownerRoles, $user->user ) && ! UserHelper::checkUserIsLoggedIn ($user->user , $params))
// 						$emails [] = $user->user->email;
// 				} else if ($complaint->owner_role == 'MNGR') {
// 					if (UserHelper::checkUserHasRole ( RoleHelper::getManagerRole(), $user->user ) && ! UserHelper::checkUserIsLoggedIn ($user->user , $params))
// 						$emails [] = $user->user->email;
// 				} else if ($complaint->owner_role == 'AMNGR') {
// 					if (UserHelper::checkUserHasRole ( RoleHelper::getAssistantManagerRole(), $user->user ) && ! UserHelper::checkUserIsLoggedIn ($user->user , $params))
// 						$emails [] = $user->user->email;
// 				} else if ($complaint->owner_role == 'DPHD') {
// 					if (UserHelper::checkUserHasRole ( RoleHelper::getDepartmentHeadRole(), $user->user ) && ! UserHelper::checkUserIsLoggedIn ($user->user , $params))
// 						$emails [] = $user->user->email;
// 				} else if ($complaint->owner_role == 'ADPHD') {
// 					if (UserHelper::checkUserHasRole ( RoleHelper::getAssistantDepartmentRole(), $user->user ) && ! UserHelper::checkUserIsLoggedIn ($user->user , $params))
// 						$emails [] = $user->user->email;
// 				}
// 			}
// 		}
// 		return $emails;
// 	}
// 	private function getRecepientEmails($params, $emails = [], $complaint){
// 		$recipientRoles = RoleHelper::getRecipientRoles ();
// 		if ($complaint->complaintRecepient->email &&
// 				UserHelper::checkUserHasAnyRole ( $recipientRoles, $complaint->complaintRecepient ) && ! UserHelper::checkUserIsLoggedIn ($complaint->complaintRecepient , $params))
// 			$emails [] = $complaint->complaintRecepient->email;
// 			return $emails;
// 	}
}