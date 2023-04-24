<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use CacheHelper;
use App\Models\Entities\User;
use Log;

class CacheKeyGenerateJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 6;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
    public function handle()
    {
    	$users = User::where('active',1)->get();
    	foreach ($users as $user){
    		CacheHelper::putStatsClearKey($user->user_id_pk);
    	}
    }
    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception){
        Log::error ( '-----------CacheKeyGenerateJob--------- >> '.json_encode($exception));
    }
}
