<?php

namespace Rafaelrglima\Database\Console\Commands;

use Illuminate\Console\Command;

class SyncRemoteToLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:syncRemoteToLocal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync remote database either by ssh or connection string with local database';

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
        $this->info("Command is working");
    }
}
