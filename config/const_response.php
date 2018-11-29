<?php

return array(
	//通用部分
	'SUCCESS' 			        => array('code' =>'0000',  'msg' =>'成功'),
	'REQ_DATA_ERROR'            => array('code' =>'9999',  'msg' =>'系统异常'),
	'CALL_API_ERROR'            => array('code' =>'9998',  'msg'=>'接口失败'),
	'CALL_API_TIMEOUT'			=> array('code' =>'9997',  'msg'=>'接口超时'),
	'REQ_CODE_NO_EXIST'			=> array('code' =>'9996',  'msg'=>'请求码不存在'),
	'FINANCE_LOCK'			    => array('code' =>'9990',  'msg'=>'财务系统锁定'),



	//用户系列
	'USER_NO_EXIST' 			=> array('code' =>'1010', 'msg' =>'账号不存在'),
	'PASSWORD_ERROR' 			=> array('code' =>'1020', 'msg' =>'密码错误'),
	'USER_NO_RIGHTS' 			=> array('code' =>'1030', 'msg' =>'没有权限'),
	'USER_MOBILE_EXIT'         	=> array('code' =>'1080', 'msg' =>'该用户已注册'),
	
	'IDNO_ERROR'				=> array('code' =>'1040', 'msg' =>'身份证格式错误'),
	'BANK_CARD_ERROR'			=> array('code' =>'1050', 'msg' =>'银行卡格式错误'),
	'AGREEMENT_UNREAD'			=> array('code' =>'1060', 'msg' =>'协议未阅读'),
	'PHONE_ERROR'				=> array('code' =>'1070', 'msg' =>'电话格式错误'),
	'USER_EXVIEW'				=> array('code' =>'1080', 'msg' =>'用户审核中'),
	'USER_AUTHERROR'     		=> array('code' =>'1090', 'msg' =>'实名认证失败'),
	'PULL_THE BLACK'            => array('code' =>'1010', 'msg' =>'此账号已注销'),
	
	//token部分
	'TOKEN_ERROR'         		=> array('code' =>'6100', 'msg' =>'登陆错误,请重新登陆'),  //TOKEN错误
	'TOKEN_EXP'           		=> array('code' =>'6200', 'msg' =>'登陆过期,请重新登陆'),  // TOKEN过期
	'TOKEN_SIGN_ERROR'    		=> array('code' =>'6300', 'msg' =>'登陆错误,请重新登陆'),  //TOKEN签名错误

	//账户系列
	'ACC_BALANCE_EXIT'         => array('code' =>'4810', 'msg' =>'账户已存在'),
	'ACC_NO_EXIST'             => array('code' =>'4820', 'msg' =>'账户不存在'),

	
	//记账系列
	'BOOK_REQ_ORDER_ERR'       => array('code' =>'8010', 'msg' =>'会计分录错误'),
	

	
	//收银系列
	'CASH_NO_ORDER'    		 => array('code' =>'1530', 'msg' =>'汇总流水不存在'),
	'CASH_NO_DETAILORDER'    => array('code' =>'1540', 'msg' =>'明细流水不存在'),
	'DETAIL_AMT_ERROR'    	 => array('code' =>'1550', 'msg' =>'明细流水的交易金额与请求记账的交易金额不一致'),
	'ORDER_AMT_ERROR'    	 => array('code' =>'1560', 'msg' =>'汇总流水的交易金额与请求记账的交易金额不一致'),
	'TARIFF_CODE_ERR'		 => array('code' =>'1570', 'msg' =>'商户资费编码未设定'),
	'NO_TARIFF_INFO'		 => array('code' =>'1580', 'msg' =>'资费设定有问题'),
	'BUSINESS_CODE_ERR'      => array('code' =>'1590', 'msg' =>'业务类型未设定'),
	'CASH_NO_TASKINFO'       => array('code' =>'1600', 'msg' =>'任务计划不存在'),
	'CREDIT_RUNNING_LOW'     => array('code' =>'1610', 'msg' =>'用户余额不足'),
	'TASK_INFO_AMT_ERROR'    => array('code' =>'1620', 'msg' =>'还款任务计划额度与请求记账的额度不一致'),

	'ACCT_TEMPLET_NO_EXIST' => array('code' =>'2010', 'msg' =>'记账模板不存在'),
	'FINANCE_ERROR' => array('code' =>'K3333', 'msg' =>'财务系统出错'),
	

	'RECEIVE_AMT_ERROR'      => array('code' =>'3100', 'msg' =>'到账金额有误'),
	'USER_NO_LEVEL'      	 => array('code' =>'3070', 'msg' =>'用户等级未设定'),
	'AGENT_NO_EXIST'      	 => array('code' =>'3110', 'msg' =>'代理商不存在'),
	'CHANNEL_MERC_NO_EXIST'  => array('code' =>'3120', 'msg' =>'通道商户不存在'),
	'CHANNEL_RATE_NO_EXIST'  => array('code' =>'3130', 'msg' =>'通道费率不存在'),

	'DEBIT_AMOUNT_ERR'  	=> array('code' =>'4010', 'msg' =>'借方金额错误'),
	'CREDIT_AMOUNT_ERR'  	=> array('code' =>'4020', 'msg' =>'贷方金额错误'),
	'DEBIT_CREDIT_ERR'  	=> array('code' =>'4030', 'msg' =>'借贷不平衡'),

	'ORDER_FAIL'     		 => array('code' =>'1640', 'msg' =>'汇总流水失败'),
	'DETAIL_ORDER_FAIL'      => array('code' =>'1650', 'msg' =>'明细流水失败'),
	'TASK_INFO_FAIL'         => array('code' =>'1660', 'msg' =>'任务计划失败'),
	'TASK_INFO_STATUS_ERR'   => array('code' =>'1670', 'msg' =>'任务计划状态不符'),

	'JOURNAL_EMPTY'  		=> array('code' =>'5010', 'msg' =>'会计分录为空'),
	'JOURNAL_EXIST'  		=> array('code' =>'5020', 'msg' =>'会计分录重复'),
	'ORDER_PLAN_INFO_EXIST' => array('code' =>'5030', 'msg' =>'保证金退款重复提交'),

	
	
	//web部分
	'LOGIN_ERR'  			=>array('code'=>'1000','msg'=>'验证错误，请重新登陆'),


	//队列部分
	'QUEUE_NO_RULES' 		  =>array('code'=>'1001','msg'=>'消息或规则不存在'),

	//短信部分
	'CAPTCHA_ERROR'			  => array('code'=>'6010','msg'=>'验证码错误'),
	'CAPTCHA_EXP'			  => array('code'=>'6020','msg'=>'验证码过期'),
	'CAPTCHA_FILE'			  => array('code'=>'6030','msg'=>'短信发送失败'),
);