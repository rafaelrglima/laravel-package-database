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

        $stg_ssh_hostname      = \Config::get('database-artisan-commands.staging_server.ssh');
        $stg_docker_hostname   = \Config::get('database-artisan-commands.staging_server.docker');
        $stg_database_name     = \Config::get('database-artisan-commands.staging_server.database');
        $stg_database_username = \Config::get('database-artisan-commands.staging_server.username');
        $stg_database_pw       = \Config::get('database-artisan-commands.staging_server.password');

        $local_hostname        = \Config::get('database-artisan-commands.local_server.hostname');
        $local_username        = \Config::get('database-artisan-commands.local_server.username');
        $local_password        = \Config::get('database-artisan-commands.local_server.password');

        $temp_filename   = $stg_database_name."_".date("Y_m_d_H_i_s").".sql.gz";
        $local_storage   = storage_path().'/database';

        \File::makeDirectory($local_storage.'/', $mode = 0777, true, true);

        $this->info("1. Starting the backup of mysql database: {$stg_database_name}.");
        exec("ssh -t {$stg_ssh_hostname} \"docker exec -it {$stg_docker_hostname} bash -c 'mysqldump -u{$stg_database_username} -p{$stg_database_pw} --events --routines --triggers --databases {$stg_database_name} | gzip > /home/{$temp_filename}'\"");

        $this->info("2. Moving backup of docker container to host machine.");
        exec("ssh -t {$stg_ssh_hostname} \"docker cp {$stg_docker_hostname}:/home/{$temp_filename} /home/{$temp_filename}\"");

        $this->info("3. Remove backup file from docker container to save space.");
        exec("ssh -t {$stg_ssh_hostname} \"docker exec -it {$stg_docker_hostname} rm /home/{$temp_filename}\"");

        $this->info("4. Copying file from remote server to local dev machine.");
        exec("scp {$stg_ssh_hostname}:/home/{$temp_filename} {$local_storage}");

        $this->info("5. Remove backup file from remote server to save space.");
        exec("ssh {$stg_ssh_hostname} \"rm /home/{$temp_filename}\"");

        $this->info("6. Dropping local database: {$stg_database_name}");
        exec("mysql -h{$local_hostname} -u{$local_username} -p{$local_password} -e 'drop database {$stg_database_name};'");

        $this->info("7. Creating local database: {$stg_database_name}");
        exec("mysql -h{$local_hostname} -u{$local_username} -p{$local_password} -e 'create database {$stg_database_name};'");

        $this->info("8. Restoring backup to our local mysql server");
        exec("gunzip < {$local_storage}/{$temp_filename} | mysql -h{$local_hostname} -u{$local_username} -p{$local_password} --database={$stg_database_name}");

        $this->info("9. Restore is complete.");
    }
}
