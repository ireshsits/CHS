<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Traits\EmailService;
use App\Traits\ImageService;
use App\Traits\WebPushService;
use App\Traits\NotificationDataService;
use App\Models\Entities\Setting;
use Log;

class NotificationJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailService, WebPushService, NotificationDataService, ImageService;
	public $tries = 6;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	protected $params;
	protected $notificationSettings;
	public function __construct($params) {
		$this->params = $params;
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
	 * Delete the job if its models no longer exist.
	 *
	 * @var bool
	 */
	public $deleteWhenMissingModels = true;
	
	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$this->notificationSetting = Setting::getNotificationSettings();
		$this->setNotificationData($this->params);
		
		if ($this->notificationSetting->EMAIL) {
			$this->emailAction($this->params);
		}
		if($this->notificationSetting->WEBPUSH){
			$this->pushAction($this->params);
		}
		
// 			Log::info ( '-----------email------sending----------disabled---------' );
// 			return true;
	}
	/**
	 * The job failed to process.
	 *
	 * @param  Exception  $exception
	 * @return void
	 */
	public function failed(\Exception $exception){
	    Log::error ( '-----------NotificationJob--------- >> '.json_encode($exception));
	}
}
