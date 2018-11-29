<?php

return array(
    //短信
    'PARNER_SMS_TEMPLATE' => array('code' => 'S0801', 'type' => 'PARNER'),
    'AGENT_SMS_TEMPLATE' => array('code' => 'S0802', 'type' => 'AGENT'),
    'MERC_SMS_TEMPLATE' => array('code' => 'S0803', 'type' => 'MERC'),
    'USER_SMS_TEMPLATE' => array('code' => 'S0804', 'type' => 'USER'),
    'RESERVE_SMS_TEMPLATE' => array('code' => 'S0805', 'type' => 'RESERVE'),

    //消息
    'USER_REGISTER_SUC' => array('code' => 'U0100', 'type' => '00', 'msg' => '注册成功', 'path' => 'news/page/news_details'),
    'USER_CARD_SUC'     => array('code' => 'U0101', 'type' => '00', 'msg' => '卡券发放成功','path' => 'news/page/news_details'),
    'USER_REWARD_SUC'   => array('code' => 'U0102', 'type' => '00', 'msg' => '奖励金到账', 'path' => 'news/page/news_details'),
    'USER_RESERVE_SUC'  => array('code' => 'J0102', 'type' => '00', 'msg' => '客户预约', 'path' => 'news/page/news_details'),

    //消息类型
    '01' => array('code' => 'message', 'type' => '普通消息', 'img' => R('webimg/msg/putong.png')),
    '02' => array('code' => 'notice', 'type' => '公告消息', 'img' => R('webimg/msg/gonggao.png')),
    '03' => array('code' => 'task', 'type' => '新闻', 'img' => R('webimg/msg/news.png')),
    '04' => array('code' => 'profit', 'type' => '分润账单消息', 'img' => R('webimg/msg/liushui.png')),
    '05' => array('code' => 'userup', 'type' => '会员升级', 'img' => R('webimg/msg/hysj.png')),
    '06' => array('code' => 'carstyle', 'type' => '车主风采', 'img' => R('webimg/msg/czfc.png')),
    '07' => array('code' => 'carsafe', 'type' => '车辆保险', 'img' => R('webimg/msg/cbx.png')),
    '08' => array('code' => 'system', 'type' => '系统消息', 'img' => R('webimg/msg/xt.png')),

    //消息标题
    'MSGTITLE' => '让渡咖啡 | 注册成功',

    //短信验证码长度
    'CODELEN' => 6,

    //同一手机号一天内短信发送次数
    'SMS_NUMBER' => 20,

);