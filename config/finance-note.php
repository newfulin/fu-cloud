<?php

/**
 * 财务系统
 */
return [
    'cashier' =>[//检查参数
        'K0110' =>[// Plus会员推荐加盟
            'CheckOrder'=>\App\Modules\Finance\Middleware\CheckParams\CheckOrder::class,
            'CheckAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckAmount::class,
            'CheckUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckUser::class,//检查用户信息
            'CheckMercTariffRate'=>\App\Modules\Finance\Middleware\CheckParams\CheckMercTariffRate::class,//检查用户资费
        ],
        'K0120' =>[// Plus会员直接加盟
            'CheckOrder'=>\App\Modules\Finance\Middleware\CheckParams\CheckOrder::class,
            'CheckAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckAmount::class,
            'CheckUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckUser::class,//检查用户信息
            'CheckMercTariffRate'=>\App\Modules\Finance\Middleware\CheckParams\CheckMercTariffRate::class,//检查用户资费
        ],
        'K0700' =>[// 用户提现
            'CheckOrder'=>\App\Modules\Finance\Middleware\CheckParams\CheckOrder::class,
            'CheckAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckAmount::class,
            'CheckUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckUser::class,//检查用户信息
            'CheckMercTariffRate'=>\App\Modules\Finance\Middleware\CheckParams\CheckMercTariffRate::class,//检查用户资费
            'CheckChannelCost'=>\App\Modules\Finance\Middleware\CheckParams\CheckChannelCost::class,//检查通道资费
            'CheckBalance'=>\App\Modules\Finance\Middleware\CheckParams\CheckBalance::class,//检查用户提现金额是否大于余额
        ],
        'check'=>[
            'CheckOrder'=>\App\Modules\Finance\Middleware\CheckParams\CheckOrder::class,//收银流水检测
            'CheckAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckAmount::class,//交易金额检测
            'CheckUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckUser::class,//检查用户信息
            'CheckMercTariffRate'=>\App\Modules\Finance\Middleware\CheckParams\CheckMercTariffRate::class,//检查用户资费
            'CheckChannelCost'=>\App\Modules\Finance\Middleware\CheckParams\CheckChannelCost::class,//检查通道资费
            'CheckBalance'=>\App\Modules\Finance\Middleware\CheckParams\CheckBalance::class,//检查用户提现金额是否大于余额
        ]

    ]
];