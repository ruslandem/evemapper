<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'app' => [
            'driver' => 'sqlite',
            'database' => database_path(env('DB_APP', '')),
        ],

        'eve' => [
            'driver' => 'sqlite',
            'database' => database_path(env('DB_EVE', '')),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */

    'migrations' => 'migrations',

];
