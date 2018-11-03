<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/18
 * Time: 14:03
 */


return [

    'env' => env('APP_ENV'), //development 开发  production 生产

    'debug' => env('APP_DEBUG', false),

    'locale' => 'cn',

    'timezone' => 'PRC',

    'token_key' => 'EHKHHP54PXKYTS2E',
    'token_exp' => '2592000',

    'log' => 'daily',

    'APP_PATH' => './public',

    'providers' => [
        Iwanli\Wxxcx\WxxcxServiceProvider::class,
    ]
];