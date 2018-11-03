<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/25
 * Time: 8:59
 */

return [
    'APP_KEY' => env('JPUSH_APP_KEY'),
    'MASTER_SECRET' => env('JPUSH_MASTER_SECRET'),
    /* ①用户卡券发放到账提醒：尊敬的六个车用户王先生您好，您购买的5张“售车奖励券”已到账，请打开六个车APP，在“我的卡券”中查看。
     * ②奖励金到账提醒：尊敬的六个车用户王先生您好，您的1张“售车奖励券”已通过审核，奖励金已发放至您的存款账户，请打开六个车APP，在“我的资产”或“我的账单”中查询。
    */
    'TYPE' => [
        'buy' => '00',  //卡券发放
        'use' => '01',  //奖励金到账
    ],
];