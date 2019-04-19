<?php

namespace Rafaelrglima\Database;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    protected $commands = [
        'rafaelrglima\database\Console\Commands\databaseSyncRemoteToLocal',
        //'V\Package\Commands\FooCommand',
        //'Vendor\Package\Commands\BarCommand',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
