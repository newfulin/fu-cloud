<?php
return [

    'NONE'           => array('code' =>'0',   'msg' =>'用户不存在'),
    'EXIST'          => array('code' =>'1',   'msg' =>'用户存在'),

    'APP_TYPE'       => array('code' =>'10',  'msg' =>'APP接入'),
    'H5_TYPE'        => array('code' =>'20',  'msg' =>'H5接入'),
    'AGENT_TYPE'     => array('code' =>'30',  'msg' =>'代理商接入'),

    'SIGN_UP'        => array('code' =>'10',  'msg' =>'注册用户'),
    'VERIFY_USER'    => array('code' =>'20',  'msg' =>'核实用户'),
    'APPROVE_USER'   => array('code' =>'30',  'msg' =>'审批用户'),
    'OPEN_BINDING'   => array('code' =>'40',  'msg' =>'开通绑定'),
    'LOCKED_USER'    => array('code' =>'50',  'msg' =>'锁定用户'),
    'OFFICIALLY'     => array('code' =>'60',  'msg' =>'正式用户'),
    'LOGOUT'         => array('code' =>'99',  'msg' =>'注销用户'),

    //用户团队最高级数
    'USER_MODEL_COUNT'        => 3,

    /**
     * 用户级别
     */
    // 游客
    'TOURIST_USER'      => array('code' => 'P0101', 'msg' => '游客用户'),
    // 普通
    'ORDINARY_USER'     => array('code' => 'P1101', 'msg' => '普通会员'),
    'VIP_USER'          => array('code' => 'P1201', 'msg' => 'VIP会员'),
    // 合作
    'AGENT_USER'        => array('code' => 'P1301', 'msg' => '代理商'),
    'MAKER_USER'        => array('code' => 'P1401', 'msg' => '合作商'),
    'CITYAGENT_USER'    => array('code' => 'P1501', 'msg' => '合伙人'),
    // 公司系统
    'MANAGER_USER'      => array('code' => 'P2101', 'msg' => '招商经理'),
    'GENERAL_USER'      => array('code' => 'P2201', 'msg' => '销售经理'),
    'TOURIST_MEMBER'    => array('code' => 'P2301', 'msg' => '市场总监'),

    //小部件,公告,文章,帮助,
    'NOTICT_TYPE'   => array('code' =>1 , 'msg' => '公告类型'),
    'SKILL_TYPE'    => array('code' =>2 , 'msg' => '文章类型'),
    'HELP_TYPE'     => array('code' =>3 , 'msg' => '帮助'),
    'NEWS_TYPE'     => array('code' =>4 , 'msg' => '新闻'),
    'COURSE_TYPE'   => array('code' =>5 , 'msg' => '使用教程'),



    'P1101' => array('field' =>'parent4','code' =>R('webimg/userLevel/1101.png'),'msg' =>'普通会员', 'amount' => 0,'levelAmount' => 0),
    'P1201' => array('field' =>'parent5','code' =>R('webimg/userLevel/1102.png'),'msg' =>'VIP会员' ,'amount' => 198.00,'levelAmount' => 0),

    'P1301' => array('field' =>'parent6','code' =>R('webimg/userLevel/1103.png'),'msg' =>'代理商','amount' => 6000.00 ),//新平台已废弃该级别//
    'P1401' => array('field' =>'parent7','code' =>R('webimg/userLevel/1104.png'),'msg' =>'合作商','amount' => 10000.00 ),
    'P1501' => array('field' =>'parent8','code' =>R('webimg/userLevel/1105.png'),'msg' =>'合伙人','amount' => 100000.00),

    'P2101' => array('field' =>'parent9','code' =>R('webimg/userLevel/1106.png'),'msg' =>'招商经理', 'amount' => 0),
    'P2201' => array('field' =>'parent10','code'=>R('webimg/userLevel/1106.png'),'msg' =>'销售经理', 'amount' => 0),
    'P2301' => array('field' =>'parent11','code'=>R('webimg/userLevel/1107.png'),'msg' =>'市场总监', 'amount' => 0),


    //用户关系
    'parent10' => array('code' =>'P2201',  'msg' =>'销售经理'),
    'parent9' => array('code' =>'P2101',  'msg' =>'招商经理'),
    'parent8' => array('code' =>'P1501',  'msg' =>'合伙人'),
    'parent7' => array('code' =>'P1401',  'msg' =>'合作商'),
    'parent6' => array('code' =>'P1301',  'msg' =>'代理商'),
    'parent5' => array('code' =>'P1201',  'msg' =>'VIP会员'),
    'parent4' => array('code' =>'P1101',  'msg' =>'普通会员'),

    //代理商编号
    'TEST_AGENT' => array('code'=>'','msg' => '正式代理商编号'),
    'FORMAL_AGENT' => array('code'=>'100020000000000','msg' => '测试代理商编号'),  //100010000000000

    //默认推荐用户
    'RECOMMEND' => '15698153970',

    //不可提现等级
    'NOTGETCASH' => ['P2301','P2201','P2101'],
];