<?php

namespace Rafaelrglima\Database;

use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    protected $commands = [
        Console\Commands\SyncRemoteToLocal::class,
        Console\Commands\SampleCommand::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->mergeConfigFrom(__DIR__ . '/../config/database-artisan-commands.php', 'database_artisan_commands');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/database-artisan-commands.php' => config_path('database-artisan-commands.php'),
            ], 'database_artisan_commands');
        }
    }
}
