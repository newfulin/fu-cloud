<?php
return [

	// 绑定分享
	'appId' => 'wx6ff4a08101358954',
	// 公众帐号
	'AppSecret' => '68c56330376b219506bcc55d467e18ac',

	// 默认ID
	'DEFAULT' => array(
		// 青岛店
		'storeId' => '1483110006864692104',
	),
	//开放平台 微信支付使用 (APP 登陆/分享所用)
	'WE_CHAT' => array(
		// 绑定支付的APPID
		'appId' => 'wxaae31db29df1f94d',
		//
		'AppSecret' => 'ab813cb857428d072f56c32d19030735',
		// 商户号
		'mchId' => '1483489122',
		// 商户支付密钥
		'key' => '0C232D5CDF4B9AE154D38CE6D6D7157E',
		// 微信支付回调地址
		'notifyUrl' => 'http://58.56.21.246/Callback',
		// 交易方式
		'tradeType' => 'NATIVE',
		'body' => '用户升级',
	),
	//公众号appid  获取微信用户信息 (WEB 登陆/分享所用)
	'SHARE' => array(
		// 绑定分享
		'appId' => 'wxaae31db29df1f94d',
		//
		'AppSecret' => 'ab813cb857428d072f56c32d19030735',
		// 商户号
		'mchId' => '1483489122',
		// 商户支付密钥
		'key' => '0C232D5CDF4B9AE154D38CE6D6D7157E',
		// 交易方式
		'tradeType' => 'JSAPI',
		// 转售回调地址
		'notifyUrl' => 'http://58.56.21.246/Callback',

		// 获取open_id
		'OpenIdUrl' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . 'wx6ff4a08101358954' . '&secret=' . '68c56330376b219506bcc55d467e18ac' . '&grant_type=authorization_code&code=',
		// 获取Token url
		'tokenUrl' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential',
		// 获取Ticket url
		'ticketUrl' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=',
		// Token缓存路径
		'root' => base_path() . '/public' . '/checkToke.php',
	),
	// 违章查询
	'VIOLATION' => array(
		'AppKey' => '24c6e8add0e01b10f3348f371e4185dd', // 违章查询所需AppKey
		'host' => 'http://v.juhe.cn/wz', // 违章查询调用地址
		'cityPath' => '/citys',
		'carPath' => '/query',
		'cityUrl' => 'http://v.juhe.cn/wz/citys',
		'violationUrl' => 'http://v.juhe.cn/wz/query',
	),
	// 笑话大全
	'JOKES' => array(
		'typeUrl' => [
			// 1.按更新时间查询笑话
			'01' => 'http://japi.juhe.cn/joke/content/list.from',
			// 2.最新笑话(不需要选择日期和排序方式)
			'02' => 'http://japi.juhe.cn/joke/content/text.from',
			// 3.按更新时间查询趣图
			'03' => 'http://japi.juhe.cn/joke/img/list.from',
			// 4.最新趣图(不需要选择日期和排序方式)
			'04' => 'http://japi.juhe.cn/joke/img/text.from',
		],
		'imgUrl' => [
			'1' => '/files/upload/common/20180521/90e68bb508a5240defe0ff02bce5c532.png',
			'2' => '/files/upload/common/20180521/feeb6d7bb5a6ddc101b4277e664ca39c.png',
			'3' => '/files/upload/common/20180521/91fc13748e358e17b5fe806ead7411bd.png',
			'4' => '/files/upload/common/20180521/db95a17c970e27d7539b644f5c28c323.png',
		],
		'logoUrl' => [
			'1' => '/files/upload/common/20180521/68be66cb4ac44002cce78b5142bcfa6a.png',
			'2' => '/files/upload/common/20180521/cde614b93aa130e6ffaa0e8b2b22c096.png',
			'3' => '/files/upload/common/20180521/47c652531d85e59af2d73318dee94d8b.png',
			'4' => '/files/upload/common/20180521/ca900ea7de1fa88eac37aca44baad158.png',
		],
		'AppKey' => 'a5dca8526116e926112762b643f9d8fd',
	),
	// 短信
	'SMS_INFO' => array(
		'url' => 'http://124.172.234.157:8180/Service.asmx/SendMessageStr',
		// 'Id' =>'1349',
		// 'Name' =>'luketongbao',
		// 'Psw' => '123456'
		'Id' => '1349',
		'Name' => 'liugeche',
		'Psw' => '992151',
	),
	// 百度定位
	'BD' => array(
		'ak' => 'qVGHg1K9H3wSDjsXk7OGYKWnYD46X8l8',
		'coordinate' => 'http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s',
		'position' => 'http://api.map.baidu.com/geocoder/v2/?location=%s&coordtype=%s&output=%s&ak=%s',
		'distance' => 'http://api.map.baidu.com/routematrix/v2/driving?origins=%s&destinations=%s&coord_type=%s&$tactics=%s&ak=%s',
	),
	// 邮件参数
	'MAIL' => array(
		'host' => 'smtp.exmail.qq.com',
		'username' => 'liugq@nxp.cn',
		'password' => 'WeRCq7Ag5ahRyjgB',
		'port' => '465',
		'address' => array(
			'0' => 'liugq@nxp.cn',
		),
	),

];

//const KEY = '4F0E0971BECD13671CEF363C42352323';
//const JSAPI = '0C232D5CDF4B9AE154D38CE6D6D7157E';