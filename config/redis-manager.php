<?php

return [

    'uri' => 'redis-manager',


    'middleware' => [

    ],

    'disable_commands' => [
        'get', 'flushdb'
    ]
];
