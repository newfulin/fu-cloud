<?php

return array(
    
    'SYS_ACCOUNT_ID'         => array('code' => '1', 'msg'     => '系统记账ID'),
    
    'ACCOUNT_BOOK_FAILED'    => array('code' => 'K9999', 'msg' => '财务记账处理失败'),
    'TRANS_ORDER_REPEATE'    => array('code' => 'K9000', 'msg' => '交易流水重复记账'),


    //账户对象
    'ACCOUNT_OBJECT_MERC'    => array('code' => '10', 'msg'    => '商户账户'),
    'ACCOUNT_OBJECT_AGENT'   => array('code' => '20', 'msg'    => '代理账户'),
    'ACCOUNT_OBJECT_CHANNEL' => array('code' => '30', 'msg'    => '通道账户'),
    'ACCOUNT_OBJECT_PRIVATE' => array('code' => '40', 'msg'    => '企业自有'),
    'ACCOUNT_OBJECT_BANK'    => array('code' => '50', 'msg'    => '外界银行'),
    'ACCOUNT_OBJECT_PAYFOR'  => array('code' => '60', 'msg'    => '代付账户'),
    'ACCOUNT_OBJECT_PARTNER' => array('code' => '70', 'msg'    => '城市合伙人'),
    'ACCOUNT_OBJECT_USER'    => array('code' => '80', 'msg'    => '用户'),

    //账户类型
    'ACCOUNT_TYPE_ASSET'     => array('code' => '10', 'msg'    => '现金账户'),
    'ACCOUNT_TYPE_CREDIT'    => array('code' => '20', 'msg'    => '信用账户'),
    'ACCOUNT_TYPE_FREEZE'    => array('code' => '30', 'msg'    => '冻结账户'),
    'ACCOUNT_TYPE_LEND'      => array('code' => '40', 'msg'    => '保证金'),  //原 -> 垫资账户
    'ACCOUNT_TYPE_POINTS'    => array('code' => '50', 'msg'    => '积分账户'),
    'ACCOUNT_TYPE_REWARD'    => array('code' => '60', 'msg'    => '红包账户'),

    //总账科目
    'RECEIVABLE'             => array('name' => '1122','msg'   => '应收账款'),
    'PAYABLE'                => array('name' => '2202','msg'   => '应付账款'),
    'MAIN_REVENUE'           => array('name' => '6001','msg'   => '主营业收入'),


    //科目类别
    'CATEGORY_ASSET'         => array('code' => '1', 'msg'     => '资产'),
    'CATEGORY_LIABILITIES'   => array('code' => '2', 'msg'     => '负债'),
    'CATEGORY_OWNERS'        => array('code' => '3', 'msg'     => '所有者权益'),
    'CATEGORY_REVENUE'       => array('code' => '4', 'msg'     => '收入'),
    'CATEGORY_EXPENSES'      => array('code' => '5', 'msg'     => '费用'),
    'CATEGORY_INCOME'        => array('code' => '6', 'msg'     => '收益'),

    //专用账户
    //私有资产类
    'PRIVATE_ASSET_POOL'     => array('code' => '100100','msg' => '私有资金池' ),
    'PRIVATE_ASSET_REWARD'   => array('code' => '100500','msg' => '企业红包账户'),

    //私有收入账户
    'PRIVATE_REVENUE'        => array('code' => '200000','msg' => '收入账户'),  
    //可增加不同业务的收入账户

    //成本账户
    'PRIVATE_COST'           => array('code' => '300000','msg' => '成本账户'),  

    //其它账户
    'PRIVATE_OUT_BANK'       => array('code' => '999999','msg' => '外界银行账户'),  
    'PRIVATE_PAY_FOR'        => array('code' => '888888','msg' => '代付账户'),    


);
