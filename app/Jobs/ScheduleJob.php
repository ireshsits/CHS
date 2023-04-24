<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Complaints\ComplaintsController;
use App\Http\Controllers\Sync\SyncController;
use App\Classes\UPM\Sync;
use Log;

class ScheduleJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $tries = 6;
	protected $complaintContr;
	protected $sync;
	protected $params;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($params) {
		$this->params = $params;
		$this->complaintContr= new ComplaintsController ();
		$this->sync= new Sync();
	}
	
	/**
	 * Determine the time at which the job should timeout.
	 *
	 * @return \DateTime
	 */
	public function retryUntil()
	{
	    return now()->addSeconds(60);
	}
	
	
	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		switch ($this->params ['type']) {
			/**
			 * Return reminder send 1 day before the return day..
			 */
			case 'CRE' :
				return $this->complaintContr->getComplaintReminder();
			/**
			 * Sync Active Directory Data.
			 */
			case 'ADSYNC' :
				return $this->syncContr->getADSync();
			/**
			 * UPM Initial sync
			 */
			case 'UPMINITIALSYNC' :
			    return $this->sync->getInitialUPMSync();
			/**
			 * UPM user sync
			 */
			case 'UPMUSERSYNC' :
			    return $this->sync->getSolWiseUsersForRole($this->params['solId']);
		}
		
	}
	/**
	 * The job failed to process.
	 *
	 * @param  Exception  $exception
	 * @return void
	 */
	public function failed(\Exception $exception){
	    Log::error ( '-----------ScheduleJob--------- >> '.json_encode($exception));
	}
}
