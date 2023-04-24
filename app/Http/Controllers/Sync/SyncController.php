<?php

namespace App\Http\Controllers\Sync;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\UPM\Sync;
use App\Models\Entities\Category;

class SyncController extends Controller
{
	public function getADSync() {
		// 		// 		try {
		
		// 		$complaints = $this->manageComplaintsRepo->getComplaintSchedulingIds ( [
		// 				'open_date' => DateHelper::getDate( -3 ),
		// 				'status' => ['INP','ESC']
		// 		] );
		// 		foreach ( $complaints as $complaint ) {
		
			// 			$this->  complaintsRepo->sendReminder ( array (
			// 					'complaint_id_pk' => $complaint->complaint_id_pk,
			// 					'role' => 'ALL',
			// 					'schedule' => true
			// 			) );
			// 		}
			
			// 		} catch ( \Exception $e ) {
			// 			Log::info ( 'Reminder Error >>' . json_encode ( $e ) );
			// 		}
	}
	public function getInitialUPMSync(){
	     $sync = new Sync();
	     $sync->getInitialUPMSync();
	}
}
