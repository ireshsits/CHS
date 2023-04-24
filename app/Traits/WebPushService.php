<?php

namespace App\Traits;

use Log;
use App;
use App\Notifications\NotifyUser;
use App\Models\Entities\User;
use Notification;

trait WebPushService
{
	public function pushAction($data) {
		try {
			switch ($data ['action']) {
				case 'notification' :
					return $this->sendPush ( $data );
					break;
				default :
					;
					break;
			}
		} catch ( \Exception $e ) {
			Log::error ( '-----------webpush------sending----------error---------' );
			Log::error ( 'webpush---------error---------' . json_encode ( $e->getMessage () ) );
			Log::error ( 'webpush---------params---------' . json_encode ( $data ) );
			return true;
		}
	}
	private function sendPush($params){
		$pushData = $this->getWebPushNotificationData($params);
		foreach ($pushData['notifyEmails'] as $email){
			$user = User::where('email',$email)->first();
			if($user){
				$user->notify ( new NotifyUser ( $this->__buildPushArray ($params, array (
						'complaint' => $pushData ['complaintArray'],
						'user' => $user,
						'url'=> $pushData['url']
				) ) ) );
			}
		}
	}
	private function __buildPushArray($params, $data) {
		$subject = $this->getPushSubject( ($params ['override_status'] ?? $data['complaint']->status) );
		$pushObject = ( object ) [
				'subject' => $subject,
				'data' => ( object ) [
						'complaint_id_pk' => $data['complaint']->complaint_id_pk,
						'reference_number' => $data['complaint']->reference_number,
						'complaint_recipient_branch' => $data['complaint']->complaint_recepient->department_user->department->name,
						'url' => $data['url']
					]
		];
// 		{#976
// 			+"subject": "Complaint Reminder"
// 					+"data": {#720
// 						+"complaint_id_pk": 1
// 						+"reference_number": "CM|2019|9|12|000001"
// 								+"complaint_recipient_branch": "wellawatte"
// 										+"url": "http://localhost:8000/dashboard/complaints/setup/mode%3Dedit%26id%3D1"
// 			}
// 		}
		return $pushObject;
	}
	private function getPushSubject($status) {
		switch ($status) {
			case 'INP'  :return 'New Complaint';break;
			case 'COM'  :return 'Complaint Completed';break;
			case 'CLO'  :return 'Complaint Closed';break;
			case 'ESC'  :return 'Complaint Escalated';break;
			case 'REJ' 	:return 'Complaint Rejected';break;
			/**
			 * Override status templates
			 */
			case 'RMDIR':return 'Complaint Reminder';break;
			case 'REREP':return 'Complaint Re replied';break;
			case 'REOPN':return 'Complaint Re-opened';break;
		}
	}
}
