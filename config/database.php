<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/29
 * Time: 13:21
 */
return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'unix_socket' => env  ('DB_SOCKET'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'mysql_center' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_CENTER'),
            'port' => env('DB_PORT_CENTER'),
            'database' => env('DB_DATABASE_CENTER'),
            'username' => env('DB_USERNAME_CENTER', 'forge'),
            'password' => env('DB_PASSWORD_CENTER', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]
    ],
    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_DRIVER','predis'),

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],


];