<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ScheduleJob;
use Log;

class DailyUPMSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:upm_sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule daily UPM Sync';

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
        Log::info ( 'Daily UPM Sync >>>>>>>>>>>>>>>>>>' );
        ScheduleJob::dispatch([
            'type' => 'UPMINITIALSYNC'
        ]);
    }
}
