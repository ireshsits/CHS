<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\BackupJob;
use App\Jobs\BackupCleanJob;
use Log;

class DailyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule backup';

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
        Log::info ( 'Backup Filesystem >>>>>>>>>>>>>>>>>>' );
        BackupCleanJob::withChain([
            new BackupJob
        ])->dispatch();
    }
}
