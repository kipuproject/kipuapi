<?php
return [
    'default' => 'master',
    'connections' => [
        'master' => [
            'driver' => 'mysql',
            'host' => env('MASTER_HOST'),
            'database' => env('MASTER_DATABASE'),
            'username' => env('MASTER_USERNAME'),
            'password' => env('MASTER_PASSWORD'),
            'prefix' => env('MASTER_PREFIX'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'adobe' => [
            'driver' => 'mysql',
            'host' => env('HOTEL_CADOBE_HOST'),
            'database' => env('HOTEL_CADOBE_DATABASE'),
            'username' => env('HOTEL_CADOBE_USERNAME'),
            'password' => env('HOTEL_CADOBE_PASSWORD'),
            'prefix' => env('HOTEL_CADOBE_PREFIX'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mastranto' => [
            'driver' => 'mysql',
            'host' => env('HOTEL_MASTRANTO_HOST'),
            'database' => env('HOTEL_MASTRANTO_DATABASE'),
            'username' => env('HOTEL_MASTRANTO_USERNAME'),
            'password' => env('HOTEL_MASTRANTO_PASSWORD'),
            'prefix' => env('HOTEL_MASTRANTO_PREFIX'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ]
];