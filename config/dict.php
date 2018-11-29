<?php

return array(
    
    'acct_booking_order' =>array(
        'account_balance_status' =>array(
            '0' => '未记账',
            '1' => '已记账',
            '2' => '记账中',
        )
    ),

    /**
     * A0551 普通用户升级VIP
     * A0552 VIP升级创客
     * A0553 普通用户升级创客
     * A0554 VIP升级服务商 10000   K0630  VIP_USER--P1201(VIP会员)             --> COOPERATION_USER--P1401(服务商合作商)
     * A0555 VIP升级合伙人 50000   K0640  VIP_USER--P1201(VIP会员)             --> CITYAGENT_USER--P1501(合伙人合作商)
     * A0556 创客升级服务商 8000    K0630  MAKER_USER--P1301(创客合作商)        --> COOPERATION_USER--P1401(服务商合作商)
     * A0557 创客升级合伙人 48000   K0640  MAKER_USER--P1301(创客合作商)        --> CITYAGENT_USER--P1501(合伙人合作商)
     * A0558 服务商升级合伙人 40000 K0640  COOPERATION_USER--P1401(服务商合作商) --> CITYAGENT_USER--P1501(合伙人合作商)
     */ 

    'tran_trans_order' =>array(
        'business_code' =>array(
            'A0130' => 'VIP邀请码升级',
            'A0131' => '总代理邀请码升级',
            'A0132' => '合伙人激活码升级',
            'A0230' => '缴费升级VIP',
            'A0231' => '缴费升级总代理',
            'A0233' => '缴费升级合伙人',
            'A0140' => '区代加盟',
            'A0150' => '市代加盟',
            'A0160' => '省代加盟',
            'A1140' => '原合伙人转区代',
            'A1233' => '原代理商/车巢转合伙人',
            'A0700' => '提现',
            'A0600' => '订单支付'
        ),
        'status' =>array(
            '1' => '交易中',
            '2' => '交易成功',
            '3' => '交易失败',
            '4' => '交易成功',
            '5' => '审核中',
            '6' => '审核中',
            '7' => '交易成功',
            '8' => '交易失败',
            '9' => '交易失败',
        )
    ),

    //红包状态
    'red_packet_status' => [
        'status' => [
            '01' => '有效期 永久',
            '02' => '已使用',
            '03' => '已过期',
        ]
    ],
/**
    <OrderStatus Value="1" Text="交易中" />
	<OrderStatus Value="2" Text="成功" />
	<OrderStatus Value="3" Text="失败" />
 	<OrderStatus Value="4" Text="报表已导出" />
	<OrderStatus Value="5" Text="人工导出成功" />
	<OrderStatus Value="6" Text="重新记账申请" />
    <OrderStatus Value="7" Text="重新记账成功" />
    <OrderStatus Value="8" Text="通道交易失败" />
	<OrderStatus Value="9" Text="关闭交易" />
 */
);