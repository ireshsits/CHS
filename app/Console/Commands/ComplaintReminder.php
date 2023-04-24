<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ScheduleJob;
use Log;


class ComplaintReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:complaint_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for remind the complain ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	Log::info ( 'Sending Complaint Reminder Email >>>>>>>>>>>>>>>>>>' );
    	ScheduleJob::dispatch ( [
    			'type' => 'CRE'
    	] );
    }
}
