<?php

namespace App\Traits;

use App\Jobs\EmailJob;
use Config;
use Log;
use DateHelper;
use App;

trait EmailService
{
// 	private function getCCCEmail() {
// 			return config ( 'mail.ccc.noreply_address' );
// 	}
	private function sendEmail($params) {
		Log::info ( 'email---------params---------' . json_encode ( $params ) );
		return EmailJob::dispatch ( $params );
	}
	public function emailAction($data) {
		try {
			switch ($data ['action']) {
				case 'notification' :
					$params = $this->__buildNotificationArray ( $data );
					return $this->sendEmail ( $params );
					break;
				default :
					;
					break;
			}
		} catch ( \Exception $e ) {
			Log::error ( '-----------email------sending----------error---------' );
			Log::error ( 'email---------error---------' . json_encode ( $e->getMessage () ) );
			Log::error ( 'email---------params---------' . json_encode ( $data ) );
			return true;
		}
	}
	private function __buildNotificationArray($params) {
		$notficationData = $this->getEmailNotificationData($params);
		$complaint = $notficationData['complaint'];
		/**
		 * If params['override_status'] exists we need to get the template related for that scheduling email.
		 *
		 * @var unknown $templateData
		 */
		$templateData = $this->getNotificationTemplate ( $params ['override_status'] ?? $complaint->status);
		$subject = $this->getNotificationEmailSubject ($complaint, ($params ['override_status'] ?? $complaint->status) );
		
// 		if (App::environment(['local'])) {
// 			$emails = [Config::get ( 'mail.test.address' )];
// 		}else {
			$emails = $notficationData['notifyEmails'];
// 		}

		$mailObject = ( object ) [ 
				'code' => $templateData->code,
				'template' => $templateData->template,
				'subject' => $subject,
				'to' => $emails,
				'from' => Config::get ( 'mail.from.address' ),
				'data' => ( object ) [ 
						'complaint_id_pk' => $complaint->complaint_id_pk,
						'reference_number' => $complaint->reference_number,
				        'complaint_recipient' => $notficationData['recipientDetails']->name.' ('.$notficationData['recipientDetails']->department.')',
				        'complaint_resolved_by' => (count($notficationData['resolvedByDetails']) > 0 ? implode(', ',$notficationData['resolvedByDetails']) : ''),
				        'complaint_closed_by' => (count($notficationData['closedByDetails']) > 0 ? implode(', ',$notficationData['closedByDetails']) : ''),
				        'complaint_reopened_by' => (count($notficationData['reopenedDetails']) > 0 ? implode(', ',$notficationData['reopenedDetails']) : ''),
						'url' => $notficationData['url'],
						'complaint' => $complaint->complaint,
						'open_date' => DateHelper::formatToDateString ( $complaint->open_date ?? null),
						'close_date' => DateHelper::formatToDateString ( $complaint->close_date ?? null),
						'logo_url' => $this->getImage ( array (
								'dir' => 'common',
								'image_name' => 'sampathBank.png'
						) ),
				    'year' => DateHelper::getCurrentYear(),
// 				    'displayTestMails' => 'To -> '.implode(',',$notficationData['notifyEmails']).' | CC ->'.(isset($notficationData['ccEmails']) && count($notficationData['ccEmails']) > 0 ? implode(',',$notficationData['ccEmails']): '')
				],
				'header' => ( object ) [ 
						'type' => $subject,
						'name' => Config::get ( 'mail.from.name' ) 
				] 
		];
		
		if (isset($notficationData['ccEmails']) && count($notficationData['ccEmails']) > 0)
		    $mailObject->cc = $notficationData['ccEmails'];
		
		return $mailObject;
	}
	private function getNotificationEmailSubject($complaint,$status) {
		switch ($status) {
		    case 'INP'  :return 'CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
		    case 'REP'  :return 'CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'COM'  :return 'CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'CLO'  :return 'CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'ESC'  :return 'Escalation of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'REJ' 	:return 'Rejection of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			/**
			 * Solution statuses
			 */
			case 'VFD'  :return 'Solution of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'ACP'  :return 'Solution of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'NACP' :return 'Solution of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'DEL' :return 'Solution of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			/**
			 * Escalation statuses
			 */
			case 'ESCTO' :return 'Escalation of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'ESCREJ':return 'Escalation of CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			/**
			 * Override status templates
			 */
			case 'RMDIR':return 'CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'REREP':return 'Complaint Re replied';break;
			case 'REOPN':return 'Reopened CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
			case 'REFWD':return 'Reforwarded CCER : Ref No. '.$complaint->reference_number.' Date : '.DateHelper::formatToDateString ( $complaint->open_date ?? null); break;
		}
	}
	private function getNotificationTemplate($status) {
		switch ($status) {
			case 'INP' :return ( object ) [ 'template' => 'dashboard.emails.new-complaint','code' => 'INPE'];break;
			case 'REP' :return ( object ) [ 'template' => 'dashboard.emails.replied-complaint','code' => 'REPE'];break;
			case 'COM' :return ( object ) [ 'template' => 'dashboard.emails.completed-complaint','code' => 'REPE'];break;
			case 'CLO' :return ( object ) [ 'template' => 'dashboard.emails.closed-complaint','code' => 'COME'];break;
			case 'ESC' :return ( object ) [ 'template' => 'dashboard.emails.escalated-complaint','code' => 'ESCE'];break;
			case 'REJ' :return ( object ) [ 'template' => 'dashboard.emails.cancelled-complaint','code' => 'REJE'];break;
			/**
			 * Solution statuses
			 */
			case 'VFD' :return ( object ) [ 'template' => 'dashboard.emails.replied-complaint','code' => 'REPE'];break;
			case 'ACP' :return ( object ) [ 'template' => 'dashboard.emails.accepted-solution','code' => 'ACPE'];break;
			case 'NACP':return ( object ) [ 'template' => 'dashboard.emails.reject-solution','code' => 'NACPE'];break;
			case 'DEL' :return ( object ) [ 'template' => 'dashboard.emails.delete-solution','code' => 'DELE'];break;
			/**
			 * Escalation statuses
			 */
			case 'ESCTO' :return ( object ) [ 'template' => 'dashboard.emails.escalated-complaint','code' => 'ESCE'];break;
			case 'ESCREJ' :return ( object ) [ 'template' => 'dashboard.emails.cancelled-complaint','code' => 'ESCE'];break;
			/**
			 * Override status templates
			 */
			case 'RMDIR' :return ( object ) [ 'template' => 'dashboard.emails.reminder-complaint','code' => 'RMDIRE'];break;
			case 'REREP' :return ( object ) [ 'template' => 'dashboard.emails.replied-complaint', 'code' => 'REREPE'];break;
			case 'REOPN' :return ( object ) [ 'template' => 'dashboard.emails.reopened-complaint', 'code' => 'REOPNE'];break;
			case 'REFWD' :return ( object ) [ 'template' => 'dashboard.emails.new-complaint', 'code' => 'REOPNE'];break;
		}
	}
}
