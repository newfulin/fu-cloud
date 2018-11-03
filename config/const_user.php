<?php
return [

	'NONE' => array('code' => '0', 'msg' => '用户不存在'),
	'EXIST' => array('code' => '1', 'msg' => '用户存在'),

	'APP_TYPE' => array('code' => '10', 'msg' => 'APP接入'),
	'H5_TYPE' => array('code' => '20', 'msg' => 'H5接入'),
	'AGENT_TYPE' => array('code' => '30', 'msg' => '代理商接入'),

	'SIGN_UP' => array('code' => '10', 'msg' => '注册用户'),
	'VERIFY_USER' => array('code' => '20', 'msg' => '核实用户'),
	'APPROVE_USER' => array('code' => '30', 'msg' => '审批用户'),
	'OPEN_BINDING' => array('code' => '40', 'msg' => '开通绑定'),
	'LOCKED_USER' => array('code' => '50', 'msg' => '锁定用户'),
	'OFFICIALLY' => array('code' => '60', 'msg' => '正式用户'),
	'LOGOUT' => array('code' => '99', 'msg' => '注销用户'),

	//用户团队最高级数
	'USER_MODEL_COUNT' => 3,

	/**
	 * 用户级别
	 */
	// 普通
	'ORDINARY_USER' => array('code' => 'P1101', 'msg' => '普通用户'),
	'MEMBER_USER' => array('code' => 'P1111', 'msg' => '会员用户'),
	'VIP_USER' => array('code' => 'P1201', 'msg' => 'VIP用户'),
	'AGENT_USER' => array('code' => 'P1301', 'msg' => '总代理'),
	'CITYAGENT_USER' => array('code' => 'P1311', 'msg' => '合伙人'),
	'AREA_USER' => array('code' => 'P1401', 'msg' => '区代'),
	'CITY_USER' => array('code' => 'P1501', 'msg' => '市代'),
	'PROVINCE_USER' => array('code' => 'P1601', 'msg' => '省代'),

	// 公司系统
	'LEASING_MANAGER' => array('code' => 'P2101', 'msg' => '招商经理'),
	'SALES_MANAGER' => array('code' => 'P2201', 'msg' => '销售经理'),
	'MARKETING_DIRECTOR' => array('code' => 'P2301', 'msg' => '市场总监'),

	//小部件,公告,文章,帮助,
	'NOTICT_TYPE' => array('code' => 1, 'msg' => '公告类型'),
	'SKILL_TYPE' => array('code' => 2, 'msg' => '文章类型'),
	'HELP_TYPE' => array('code' => 3, 'msg' => '帮助'),
	'NEWS_TYPE' => array('code' => 4, 'msg' => '新闻'),
	'COURSE_TYPE' => array('code' => 5, 'msg' => '使用教程'),

	'P1101' => array('field' => '', 'code' => R('webimg/userLevel/P1101.png'), 'msg' => '普通用户', 'amount' => 0, 'levelAmount' => 0),
	'P1111' => array('field' => '', 'code' => R('webimg/userLevel/P1111.png'), 'msg' => '会员用户', 'amount' => 0, 'levelAmount' => 0),
	'P1201' => array('field' => '', 'code' => R('webimg/userLevel/P1201.png'), 'msg' => 'VIP用户', 'amount' => 1000.00, 'levelAmount' => 0),
	'P1301' => array('field' => '', 'code' => R('webimg/userLevel/P1301.png'), 'msg' => '总代理', 'amount' => 10000.00, 'levelAmount' => 0),
	'P1311' => array('field' => '', 'code' => R('webimg/userLevel/P1311.png'), 'msg' => '合伙人', 'amount' => 30000.00, 'levelAmount' => 0),
	'P1401' => array('field' => '', 'code' => R('webimg/userLevel/P1401.png'), 'msg' => '区代', 'amount' => 100000.00, 'levelAmount' => 0),
	'P1501' => array('field' => '', 'code' => R('webimg/userLevel/P1501.png'), 'msg' => '市代', 'amount' => 300000.00, 'levelAmount' => 0),
	'P1601' => array('field' => '', 'code' => R('webimg/userLevel/P1601.png'), 'msg' => '省代', 'amount' => 1000000.00, 'levelAmount' => 0),

	'P2101' => array('field' => '', 'code' => R('webimg/userLevel/P2101.png'), 'msg' => '招商经理', 'amount' => 6000.00),
	'P2201' => array('field' => '', 'code' => R('webimg/userLevel/P2201.png'), 'msg' => '销售经理', 'amount' => 10000.00),
	'P2301' => array('field' => '', 'code' => R('webimg/userLevel/P2301.png'), 'msg' => '市场总监', 'amount' => 100000.00),

	//代理商编号
	'TEST_AGENT' => array('code' => '', 'msg' => '正式代理商编号'),
	'FORMAL_AGENT' => array('code' => '100020000000000', 'msg' => '测试代理商编号'), //100010000000000

	//默认推荐用户
	'RECOMMEND' => '15698153970',

	//不可提现等级
	'NOTGETCASH' => [],

	//不可充值等级
	'NOTRECHARGE' => ['P2301', 'P2201', 'P2101'],

	//不可实名等级
	'NOTINFOAUTH' => ['P2301', 'P2201', 'P2101'],

	//最少提现金额
	'GETCASHRANGE' => '200',

	//所有等级
	'LEVEL' => [
		'1' => 'P1101',
		'2' => 'P1111',
		'3' => 'P1201',
		'4' => 'P1301',
		'5' => 'P1311',
		'6' => 'P1401',
		'7' => 'P1501',
		'8' => 'P1601',
		'9' => 'P2101',
		'10' => 'P2201',
		'11' => 'P2301',
	],
];