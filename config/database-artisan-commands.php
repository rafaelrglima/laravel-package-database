<?php
return [

    /*
    |--------------------------------------------------------------------------
    | SSH Vhost config name
    |--------------------------------------------------------------------------
    |
    | Here is the ssh config name defined inside ~/.ssh/config like below:
    |
    | Host remotesshserver
    | HostName 99.99.99.99
    | User root
    | IdentityFile ~/.ssh/yoursshkey
    |
    */
    'staging_server' => [
        'ssh' => env('STG_SSH_HOSTNAME', 'remotesshserver'),
        'docker' => env('STG_DOCKER_HOSTNAME', 'app-mysql'),
        'database' => env('STG_DATABASE_NAME', 'appdb'),
        'username' => env('STG_DATABASE_USER', 'dbusername'),
        'password' => env('STG_DATABASE_PW', 'dbpassword')
    ],


    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are extra database connections that can be used for local development
    |
    */
    'local_server' => [
        'hostname' => env('LOCAL_HOSTNAME', 'localhost'),
        'username' => env('LOCAL_DATABASE_NAME', 'appdb'),
        'password' => env('LOCAL_DATABASE_PW', 'appdb')
    ],
];
