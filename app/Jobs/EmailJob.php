<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use App\Mail\SendMail;
use Log;

class EmailJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $tries = 6;
	protected $params;
	
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
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
		Mail::send ( new SendMail ( $this->params ) );
		if( count(Mail::failures()) > 0 ) {
			foreach(Mail::failures as $email_address) {
				Log::error ( 'email---sending---failed---for---' . json_encode ($email_address) );
			}
			return false;
		}else{
			Log::info ( 'emails---sent---successfully');
			return true;
		}
	}
	/**
	 * The job failed to process.
	 *
	 * @param  Exception  $exception
	 * @return void
	 */
	public function failed(\Exception $exception){
	    Log::error ( '-----------EmailJob--------- >> '.json_encode($exception));
	}
}
