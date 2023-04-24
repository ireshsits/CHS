<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ScheduleTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron_task_schedular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cron job task scheduling';

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
    	Log::info ( 'Cron Job Task Schedular working >>>>>>>>>>>>>>>>>>' );
    }
}
