    <?php
return array(
	//  轮播图
	'slide' => array(
		'0' => array('slide_src' => R('webimg/slide/banner6.png')),
		'1' => array('slide_src' => R('webimg/slide/banner1.png')),
		'2' => array('slide_src' => R('webimg/slide/banner2.png')),
		'3' => array('slide_src' => R('webimg/slide/banner3.png')),
		'4' => array('slide_src' => R('webimg/slide/banner4.png')),
		'5' => array('slide_src' => R('webimg/slide/banner5.png')),

	),
	'share' => array('src' => R('webimg/slide/share.png')),
	'upgrade' => array('src' => R('webimg/slide/upgrade.png')),

	//明细流水账单  图标
	'trans_order_status_img' => array(
		'A0110' => R('webimg/icon/upgrade.png'),
		'A0120' => R('webimg/icon/upgrade.png'),
		'A0200' => R('webimg/icon/to_pay.png'),
		'A0700' => R('webimg/icon/put.png'),
		'A0210' => R('webimg/icon/to_pay.png'),
		'A0220' => R('webimg/icon/to_pay.png'),
		'A0310' => R('webimg/icon/put.png'),
		'A0330' => R('webimg/icon/put.png'),
		'A0350' => R('webimg/icon/put.png'),
		'A0360' => R('webimg/icon/reduce.png'),
	),

	//汽车服务文章json
	'car_article' => [
		0 => [
			'title' => '潘长江豪车无数，路虎，道奇公羊随便上路',
			'create_time' => '2018-03-26 08:23',
			'img' => [
				0 => R('article/img/article1.png'),
				1 => R('article/img/article2.png'),
				2 => R('article/img/article3.png'),
			],
			'url' => 'articleOne',
		],
		1 => [
			'title' => '初次购车如何选？八步教你轻松搞定',
			'create_time' => '2018-3-26 10:16',
			'img' => [
				0 => R('article/img/article4.png'),
			],
			'url' => 'articleTwo',
		],
		2 => [
			'title' => '新交规下这5种违章直接就扣掉12分',
			'create_time' => '2018-3-27 14:25',
			'img' => [
				0 => R('article/img/article5.png'),
			],
			'url' => 'articleThree',
		],
	],
	'cafe_poster' => R('webimg/param/2.png'),
	'assets_icon' => [
		'10' => ['icon' => R('webimg/icon/balance_icon.png')],
		'20' => ['icon' => ''],
		'30' => ['icon' => R('webimg/icon/freeze_icon.png')],
		'40' => ['icon' => R('webimg/icon/bean_icon.png')],
		'50' => ['icon' => R('webimg/icon/RD.png')],
		'60' => ['icon' => R('webimg/icon/redPacket_icon.png')],
	],
	'goods_order' => [
		'10' => '待支付',
		'20' => '支付成功',
		'25' => '待收货',
		'30' => '交易失败',
		'40' => '订单已取消',
		'50' => '订单已删除',
		'60' => '交易成功',
	],
	'share_profit' => [
		'profit_ratio' => 10, //商品分润比例
		'parent1_ratio' => 60, //直推分润比例
		'parent2_ratio' => 40, //间推分润比例
	],
);