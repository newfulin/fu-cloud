<?php

/**
 * 财务系统
 */
return [
    'cashier' =>[//检查参数
        'check'=>[
            'CheckOrder'=>\App\Modules\Finance\Middleware\CheckParams\CheckOrder::class,//收银流水检测
            'CheckAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckAmount::class,//交易金额检测
            'CheckUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckUser::class,//检查用户信息
            'CheckToUser'=>\App\Modules\Finance\Middleware\CheckParams\CheckToUser::class,//检查收款方用户信息
            'CheckMercTariffRate'=>\App\Modules\Finance\Middleware\CheckParams\CheckMercTariffRate::class,//检查用户资费
            'CheckChannelCost'=>\App\Modules\Finance\Middleware\CheckParams\CheckChannelCost::class,//检查通道资费
            'CheckBalance'=>\App\Modules\Finance\Middleware\CheckParams\CheckBalance::class,//检查用户提现金额是否大于余额
            'CheckCoffeeBean'=>\App\Modules\Finance\Middleware\CheckParams\CheckCoffeeBean::class,//检查账户金豆余额
            'CheckCodeAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckCodeAmount::class,//检查数据字典配置金额
            'CheckProductCostAmount'=>\App\Modules\Finance\Middleware\CheckParams\CheckProductCostAmount::class,//检查产品成本(订单)
        ]
    ],
    'policy' => [//策略配置中间件
        'K0600' =>[]
    ],

    'middle_process' => [//记账模板生成 流水 处理      1.
        'ChannelBean'=>\App\Modules\Finance\Middleware\Process\ChannelBean::class,
        'SystemBean' =>\App\Modules\Finance\Middleware\Process\SystemBean::class,
        'MarkModel'  =>\App\Modules\Finance\Middleware\Process\MarkModel::class,
        'SalesProfit'=>\App\Modules\Finance\Middleware\Process\SalesProfit::class,
        'UserNumber'=>\App\Modules\Finance\Middleware\Process\UserNumber::class,
        'ToUserNumber'=>\App\Modules\Finance\Middleware\Process\ToUserNumber::class,
        'UserNumberHb'=>\App\Modules\Finance\Middleware\Process\UserNumberHb::class,
        'ChannelNumber'=>\App\Modules\Finance\Middleware\Process\ChannelNumber::class,
    ],

    'process_bean' =>[//获取具体记账关联 账户 (系统账户\用户编号等)    2.
        'UserNumber'=>\App\Modules\Finance\Bean\ProcessBean\UserNumber::class,
        'ToUserNumber'=>\App\Modules\Finance\Bean\ProcessBean\ToUserNumber::class,
        'UserNumberHb'=>\App\Modules\Finance\Bean\ProcessBean\UserNumberHb::class,
        'ChannelNumber'=>\App\Modules\Finance\Bean\ProcessBean\ChannelNumber::class,
        'SystemBean' =>\App\Modules\Finance\Bean\ProcessBean\SystemBean::class,
        'MarkModel'  =>\App\Modules\Finance\Bean\ProcessBean\MarkModel::class,
        'SalesProfit'=>\App\Modules\Finance\Bean\ProcessBean\SalesProfit::class,
    ],

    'account_bean' => [//获取套账号
        'System'=>\App\Modules\Finance\Bean\AccountBean\SystemtAccount::class

    ],
    'amount_bean' =>[//计算 获取 借贷  金额******************************************
        'ChannelCost'=>\App\Modules\Finance\Bean\AmountBean\ChannelCost::class,//通道成本
        'ReceiveAmount'=>\App\Modules\Finance\Bean\AmountBean\ReceiveAmount::class,//到账金额
        'TransAmount'=>\App\Modules\Finance\Bean\AmountBean\TransAmount::class,//交易金额
        'SalesProfitAmount'=>\App\Modules\Finance\Bean\AmountBean\SalesProfitAmount::class,//0
        'PromotionAmount'=>\App\Modules\Finance\Bean\AmountBean\PromotionAmount::class,
        'RemainProfit'     =>\App\Modules\Finance\Bean\AmountBean\RemainProfit::class,//剩余金额
        'CodeAmount'     =>\App\Modules\Finance\Bean\AmountBean\CodeAmount::class,//配置金额
        'ProductCostAmount'     =>\App\Modules\Finance\Bean\AmountBean\ProductCostAmount::class//产品成本(订单)
    ],

    'mark_model' => [//营销模式分润
        'K0600'=>\App\Modules\Finance\Cashier\K0600::class,// 订单支付
        'K0140'=>\App\Modules\Finance\Cashier\K0140::class,// 区代加盟
        'K0150'=>\App\Modules\Finance\Cashier\K0150::class,// 市代加盟
        'K0160'=>\App\Modules\Finance\Cashier\K0160::class,// 省代加盟
        'K0230'=>\App\Modules\Finance\Cashier\K0230::class,// VIP
        'K0231'=>\App\Modules\Finance\Cashier\K0231::class,// 总代理
        'K0233'=>\App\Modules\Finance\Cashier\K0233::class,// 合伙人
    ],

    'bookingupdate' =>[//更新账户余额[通过记账流水相关]
        'GetNeedBookOrder' =>App\Modules\Finance\Middleware\BookingProcess\GetNeedBookOrder::class,
        'CheckBalanceOrder' =>App\Modules\Finance\Middleware\BookingProcess\CheckBalanceOrder::class,
    ],


    //---------------------------------------------------------------------------------
    'ACCOUNT_BOOK_FAILED'      => array('code' => 'K9999', 'msg' => '财务记账处理失败'),
    'TRANS_ORDER_REPEATE'      => array('code' => 'K9000', 'msg' => '交易流水重复记账'),

    'STATUS_PROCESS'         => ['code' =>'1', 'msg' =>'交易中'],
    'STATUS_SUCCESS'         => ['code' =>'2', 'msg' =>'成功'],
    'STATUS_FAIL'            => ['code' =>'3', 'msg' =>'失败'],
    'STATUS_EXPORT'          => ['code' =>'4', 'msg'=>'报表已导出'],
    'STATUS_APPROVED'        => ['code' =>'5', 'msg'=>'审核中'],
    'STATUS_REQ_ACCT'        => ['code' =>'6', 'msg'=>'重新记账申请'],
    'STATUS_ACCT_OK'         => ['code' =>'7', 'msg'=>'重新记账成功'],
    'STATUS_CHANNEL_FAIL'    => ['code' =>'8', 'msg'=>'通道交易失败'],
    'STATUS_FINISH'          => ['code' =>'9', 'msg'=>'关闭交易'],
    //账户对象
    'ACCOUNT_OBJECT_MERC'      => ['code' => '10', 'msg'    => '商户账户'],
    'ACCOUNT_OBJECT_AGENT'     => ['code' => '20', 'msg'    => '代理账户'],
    'ACCOUNT_OBJECT_CHANNEL'   => ['code' => '30', 'msg'    => '通道账户'],
    'ACCOUNT_OBJECT_PRIVATE'   => ['code' => '40', 'msg'    => '企业自有'],
    'ACCOUNT_OBJECT_PARTNER'   => ['code' => '70', 'msg'    => '城市合伙人'],
    'ACCOUNT_OBJECT_USER'      => ['code' => '80', 'msg'    => '用户账户'],
    //账户类型
    'ACCOUNT_TYPE_ASSET'       => ['code' => '10', 'msg'    => '现金'],
    'ACCOUNT_TYPE_CREDIT'      => ['code' => '20', 'msg'    => '信用'],
    'ACCOUNT_TYPE_FREEZE'      => ['code' => '30', 'msg'    => '冻结'],
    'ACCOUNT_TYPE_LEND'        => ['code' => '40', 'msg'    => '金豆'],

    'ACCOUNT_TYPE_POINTS'      => ['code' => '50', 'msg'    => '积分'],//原积分账户
    'ACCOUNT_TYPE_REWARD'      => ['code' => '60', 'msg'    => '红包'],
    //总账科目
    'RECEIVABLE'               => ['name' => '1122','msg'   => '应收账款'],//资产类
    'PAYABLE'                  => ['name' => '2202','msg'   => '应付账款'],//负债类
    'MAIN_REVENUE'             => ['name' => '1122','msg'   => '主营业收入'],
    //科目类别
    'CATEGORY_ASSET'           => array('code' => '1', 'msg'     => '资产'),
    'CATEGORY_LIABILITIES'     => array('code' => '2', 'msg'     => '负债'),
    'CATEGORY_OWNERS'          => array('code' => '3', 'msg'     => '所有者权益'),
    'CATEGORY_REVENUE'         => array('code' => '4', 'msg'     => '收入'),
    'CATEGORY_EXPENSES'        => array('code' => '5', 'msg'     => '费用'),
    'CATEGORY_INCOME'          => array('code' => '6', 'msg'     => '收益'),

    //借贷判断
    'ACCOUNT_CATEGORY'         =>array(
        '1002'  =>'CATEGORY_ASSET',
        '1122'  =>'CATEGORY_ASSET',
        '2202' =>'CATEGORY_LIABILITIES',
        '6001' =>'CATEGORY_INCOME',
        '6401' =>'CATEGORY_EXPENSES'
    ),


    //记账
    'BOOK_BALANCE_NO_UPDATE'   => ['code' => '0','msg'      => '余额未更新状态'],
    'BOOK_BALANCE_UPDATED'     => ['code' => '1','msg'      => '余额已更新状态'],
    'BOOK_BALANCE_WAITUPDATED' => ['code' => '2','msg'      => '余额更新中'],

    'BOOK_ORDER_MAKE'          => ['code' => '10','msg'     => '制单'],
    'BOOK_ORDER_APPROVE'       => ['code' => '20','msg'     => '审核'],
    'BOOK_ORDER_BOOKING'       => ['code' => '30','msg'     => '记账'],

    //银行存款                     => 1002
    '100200'                   => ['code' => '100200','msg' => '资金池'],
    '100201'                   => ['code' => '100201','msg' => '红包'],//红包账户
    '100202'                   => ['code' => '100202','msg' => '积分账户'],//积分账户
    //资产类  应收账款                     => 1122  贷减  借加
    '112200'                   => ['code' => '112200','msg' => '企业应收'],
    '112201'                   => ['code' => '112201','msg' => '代付通道'],//******** */
    //'112280'                   => ['code' => '112280','msg' => 'RD贝总资产账户'],
    //负债类  应付账款                     => 2202  贷加  借减
    '220200'                   => ['code' => '220200','msg' => '企业应付'],
    '220280'                   => ['code' => '220280','msg' => 'RD贝总负债账户'],
    //'220281'                   => ['code' => '220281','msg' => 'RD贝活动负债账户'],
    //'220282'                   => ['code' => '220282','msg' => 'RD贝企业负债账户'],
    //'220283'                   => ['code' => '220283','msg' => 'RD贝员工负债账户'],
    '220201'                   => ['code' => '220201','msg' => '代扣通道'],//******** */
    //'220202'                   => ['code' => '220202','msg' => '咖啡豆代收应付款'],//******** */
    //'220203'                   => ['code' => '220203','msg' => '产品成本应付款'],//******** */
    //'220210'                   => ['code' => '220210','msg' => '会议企业代收应付款'],//会议企业代收应付款
    '220299'                   => ['code' => '220299','msg' => '外界银行'],
    //主营收入                     => 6001
    '600100'                   => ['code' => '600100','msg' => '主营收入'],
    '600101'                   => array('code' => '600101','msg' => '会议毛收入'),
    '600102'                   => array('code' => '600102','msg' => '会议企业收入'),
    //主营成本                     => 6401   借加 贷减
    '640100'                   => ['code' => '640100','msg' => '主营成本'],
    '640101'                   => ['code' => '640101','msg' => '微信支付通道成本'],//******** */
    '640102'                   => ['code' => '640102','msg' => '支付通道成本'],//******** */
    '640103'                   => ['code' => '640103','msg' => 'POS机收款费率成本'],//******** */
    //'640104'                   => ['code' => '640104','msg' => '咖啡豆成本'],//******** */
    '640105'                   => ['code' => '640105','msg' => '保险分润成本'],//******** */
    '640106'                   => ['code' => '640106','msg' => '未启用成本账户'],//******** */
];