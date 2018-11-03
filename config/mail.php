<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/29
 * Time: 18:23
 */

return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.exmail.qq.com'),
    'port' => env('MAIL_PORT', 465),
    'from' => ['address' => 'liugq@nxp.cn', 'name' => '刘'],
    'encryption' => env('MAIL_ENCRYPTION', null),
    'username' => 'liugq@nxp.cn',
    'password' => 'WeRCq7Ag5ahRyjgB',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
];