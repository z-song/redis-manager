<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Base Path
    |--------------------------------------------------------------------------
    |
    | Base path for Redis Manager
    |
    */

    'base_path' => 'redis-manager',

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Middleware
    |--------------------------------------------------------------------------
    |
    | The Redis Manager's route middleware.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Results Per Page
    |--------------------------------------------------------------------------
    |
    | Here you can configure for the number of results will show in the
    | Redis Manager search page.
    |
    */

    'results_per_page' => 50,

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Disable Commands
    |--------------------------------------------------------------------------
    |
    | The commands listed here was disabled when you use Redis Manager Console
    | to run commands. Feel free to add commands here which you do not want
    | users to use.
    |
    */

    'disable_commands' => [
        'flushdb',
    ],
];
