<?php


return [

    'business' => [
        'A0710' => [
            \App\Modules\Transaction\Middleware\Business\A0710::class,
            \App\Modules\Transaction\Middleware\CashierMiddleware::class,
            \App\Modules\Transaction\Middleware\ChannelTransaction::class
        ]
    ],
    'channel' =>[
        'weixin' => \App\Modules\Transaction\Channel\Weixin::class
    ],



    'CASH_TYPE' => array(
        'get_cash_t0' =>'01',// T0提现
        'get_cash_t1'   =>'02',// T1提现

    ),


];