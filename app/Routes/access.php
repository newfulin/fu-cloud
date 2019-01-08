<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

//$router->get('/', 'CommUserInfoController@index');

// 不需要身份验证的请求
$router->group([], function () use ($router) {
	$router->post('/Mem.memCentre', 'MemCentreCon@memCentre');

	//公共部分 Service
	//根据城市,银行 获取银联列表 1
	$router->post('/Common.getAreaBankList', ['uses' => 'CommonController@getAreaBankList', 'as' => 'Common.getAreaBankList']);
	//查询银行接口
	$router->post('/Common.getSupportBankList', ['uses' => 'CommonController@getSupportBankList', 'as' => 'Common.getSupportBankList']);
	//查询省
	$router->post('/Common.getProvinceList', ['uses' => 'CommonController@getProvinceList', 'as' => 'Common.getProvinceList']);
	//查询市
	$router->post('/Common.getCityList', ['uses' => 'CommonController@getCityList', 'as' => 'Common.getCityList']);
	//联系方式
	$router->post('/Common.getContactInfo', ['uses' => 'CommonController@getContactInfo', 'as' => 'Common.getContactInfo']);
	//获取公众号 AppId
	$router->post('/Common.getWxConfigInfo', ['uses' => 'CommonController@getWxConfigInfo', 'as' => 'Common.getWxConfigInfo']);

//小部件
	//获取首页小部件
	$router->post('/Widget.getWidgetBanner', ['uses' => 'WidgetController@getWidgetBanner', 'as' => 'Widget.getWidgetBanner']);
	//公告列表
	$router->post('/Notice.getNoticeList', ['uses' => 'CommNoticeController@getNoticeList', 'as' => 'Notice.getNoticeList']);
	//公告详情
	$router->post('/Notice.getNoticeInfo', ['uses' => 'CommNoticeController@getNoticeInfo', 'as' => 'Notice.getNoticeInfo']);
	//首页广告模块
	$router->post('/AD.getAdModularList', ['uses' => 'ADModularController@getAdModularList', 'as' => 'AD.getAdModularList']);

//咖啡头条
	//头条菜单列表
	//    $router->post('/HeadLine.getHeadLineMenu', ['uses' => 'HeadLineController@getHeadLineMenu','as' => 'HeadLine.getHeadLineMenu']);
	//头条文章列表
	//    $router->post('/HeadLine.getHeadLineList', ['uses' => 'HeadLineController@getHeadLineList','as' => 'HeadLine.getHeadLineList']);
	//头条详情
	//    $router->post('/HeadLine.getHeadLineInfo', ['uses' => 'HeadLineController@getHeadLineInfo','as' => 'HeadLine.getHeadLineInfo']);
	//头条详情
	//    $router->post('/HeadLine.setIncLike', ['uses' => 'HeadLineController@setIncLike','as' => 'HeadLine.setIncLike']);

//定位
	//获取坐标
	$router->post('/Location.getCoordinate', ['uses' => 'LocationController@getCoordinate', 'as' => 'Location.getCoordinate']);
	//获取定位信息
	$router->post('/Location.getPosition', ['uses' => 'LocationController@getPosition', 'as' => 'Location.getPosition']);
	//获取距离
	//    $router->post('/Location.getDistance', ['uses' => 'LocationController@getDistance','as' => 'Location.getDistance']);
	//获取直线距离
	//    $router->post('/Location.calDistance', ['uses' => 'LocationController@calDistance','as' => 'Location.calDistance']);

//注册
	//用户注册
	$router->post('/Register.userRegister', ['uses' => 'UserRegisterController@userRegister', 'as' => 'Register.userRegister']);
	//市场总监创建
	//    $router->post('/ARegister.AUserRegister', ['uses' => 'AUserRegisterController@userRegister','as' => 'ARegister.AUserRegister']);

//登陆
	//手机号登陆
	$router->post('/Login.doLogin', ['uses' => 'LoginController@doLogin', 'as' => 'Login.doLogin']);
	//微信登陆
	$router->post('/WxLogin.wxDoLogin', ['uses' => 'WxLoginController@wxDoLogin', 'as' => 'WxLogin.wxDoLogin']);
	//小程序登陆
	$router->post('/WxxCx.WxxCxLogin', ['uses' => 'WxxCxLoginController@handle', 'as' => 'WxxCxLoginController.WxxCxLogin']);
	//咖啡机店主登陆
	//    $router->post('/Login.cafeLogin', ['uses' => 'LoginController@cafeLogin','as' => 'Login.cafeLogin']);

//短信
	$router->post('/Sms.sendSms', ['uses' => 'SmsController@sendSms', 'as' => 'Sms.sendSms']);

//分享
	//获取注册分享列表
	$router->post('/Share.getWechatShareInfo', ['uses' => 'ShareController@getWechatShareInfo', 'as' => 'Share.getWechatShareInfo']);
	//获取注册分享信息
	$router->post('/Share.getShareInfo', ['uses' => 'ShareController@getShareInfo', 'as' => 'Share.getShareInfo']);
	//获取会议分享
	//    $router->post('/Share.getMeetShareInfo', ['uses' => 'ShareController@getMeetShareInfo','as' => 'Share.getMeetShareInfo']);
	//获取二次
	$router->post('/Share.webShare', ['uses' => 'ShareController@webShare', 'as' => 'Share.webShare']);

//等级权益
	$router->post('/Rights.getLevelRights', ['uses' => 'LevelRightsController@getLevelRights', 'as' => 'Rights.getLevelRights']);

//升级功能简介
	//获取功能介绍列表
	$router->post('/UpdateBrief.getIntroduceList', ['uses' => 'UpdateBriefController@getIntroduceList', 'as' => 'UpdateBrief.getIntroduceList']);
	//获取功能介绍详情
	$router->post('/UpdateBrief.getIntroduceInfo', ['uses' => 'UpdateBriefController@getIntroduceInfo', 'as' => 'UpdateBrief.getIntroduceInfo']);

//用户
	//忘记密码
	$router->post('/User.forgetPassWord', ['uses' => 'CommUserInfoController@forgetPassWord', 'as' => 'User.forgetPassWord']);
// ---------------------------
	//原六个车用户邀请码升级  六个车合伙人  合作商|车巢|代理商
	$router->post('/Invite.oldUserUpgrade', ['uses' => 'InviteCodeController@oldUserUpgrade', 'as' => 'Invite.oldUserUpgrade']);

	//查询推荐上三级
	$router->post('/Team.getABCRecommendAll', ['uses' => 'TeamController@getABCRecommendAll', 'as' => 'Team.getABCRecommendAll']);

// ---------------------------------------Neko------------------------------------------------------------------------------------------
	// 商品
	// 获取商品购买记录信息
	$router->post('/Goods.buyRecord', 'GoodsCon@buyRecord');
	// 获取我喜欢的商品列表
	$router->post('/Goods.getLikeGoodsList', 'GoodsCon@getLikeGoodsList');
	// 获取商品列表
	$router->post('/Goods.getGoodsList', 'GoodsCon@getGoodsList');
	// 获取商品详情
	$router->post('/Goods.getGoodsInfo', 'GoodsCon@getGoodsInfo');
	// 红包
	$router->post('/Reward.redPacket', 'RewardCon@redPacket');
	//热品推荐
	$router->post('/Goods.getRecommendGoods', 'GoodsCon@getRecommendGoods');
	//根据分类查询
	$router->post('/Goods.sortGoodsList', 'GoodsCon@sortGoodsList');
	//数据查询
	$router->post('/Goods.getSearchList', 'GoodsCon@getSearchList');
	//模糊查询
	$router->post('/Goods.getSearchName', 'GoodsCon@getSearchName');
	//获取产品二维码
	$router->post('/getCode', 'GoodsCon@getCode');

	//获取商品分类
	$router->post('/Goods.getClassify', 'GoodsCon@getClassify');

	//获取首页分类
	$router->post('/getHomeClassify', 'GoodsCon@getHomeClassify');

//团队关系操作
	//团队关系直接切换
	$router->post('/Team.directSwitchTeamRelations', ['uses' => 'TeamController@directSwitchTeamRelations', 'as' => 'Team.directSwitchTeamRelations']);

//邀请码检测
	$router->post('/Common.checkInvitationCodeNumber', ['uses' => 'CommonController@checkInvitationCodeNumber', 'as' => 'Common.checkInvitationCodeNumber']);
});

//pms 接口
$router->group([], function () use ($router) {
	$router->post('/Team.getSuperiorRecommendAll', ['uses' => 'TeamController@getSuperiorRecommendAll', 'as' => 'Team.getSuperiorRecommendAll']);
});

// 需要身份验证的接口
$router->group(['middleware' => ['auth']], function () use ($router) {

	//获取订阅信息
	$router->post('/Wx.getSubscribe', ['uses' => 'ShareController@getSubscribe', 'as' => 'Wx.getSubscribe']);

	//我的积分充值界面
	$router->post('/BuyPoint.myPointBalance', ['uses' => 'BuyPointCon@myPointBalance', 'as' => 'BuyPoint.myPointBalance']);
	//积分充值
	$router->post('/BuyPoint.buyPoint', ['uses' => 'BuyPointCon@buyPoint', 'as' => 'BuyPoint.buyPoint']);

	//商品  收藏
	$router->post('/Collection.collectionData', ['uses' => 'CollectionController@collectionData', 'as' => 'Collection.collectionData']);
	//取消收藏
	$router->post('/Collection.cancelCollect', ['uses' => 'CollectionController@cancelCollect', 'as' => 'Collection.cancelCollect']);
	//判断是否收藏
	$router->post('/Collection.judgeCollect', ['uses' => 'CollectionController@judgeCollect', 'as' => 'Collection.judgeCollect']);
	//我的收藏
	$router->post('/Collection.myCollect', ['uses' => 'CollectionController@myCollect', 'as' => 'Collection.myCollect']);
	//获取收藏数量
	$router->post('/Collection.getCollectCount', ['uses' => 'CollectionController@getCollectCount', 'as' => 'Collection.getCollectCount']);

//点赞
	//数据点赞
	//    $router->post('/ClickCount.dataClick', ['uses' => 'ClickCountController@dataClick','as' => 'ClickCount.dataClick']);
	//获取点赞数量
	//    $router->post('/ClickCount.getClickCount', ['uses' => 'ClickCountController@getClickCount','as' => 'ClickCount.getClickCount']);
	//点赞状态
	//    $router->post('/ClickCount.judgeDataClick', ['uses' => 'ClickCountController@judgeDataClick','as' => 'ClickCount.judgeDataClick']);
	//取消收藏
	//    $router->post('/ClickCount.cancelDataClick', ['uses' => 'ClickCountController@cancelDataClick','as' => 'ClickCount.cancelDataClick']);

//用户
	//用户信息
	$router->post('/User.getUserInfo', ['uses' => 'CommUserInfoController@getUserInfo', 'as' => 'User.getUserInfo']);
	//修改用户名
	$router->post('/User.updateUserName', ['uses' => 'CommUserInfoController@updateUserName', 'as' => 'User.updateUserName']);
	//修改密码
	$router->post('/User.updatePassword', ['uses' => 'CommUserInfoController@updatePassword', 'as' => 'User.updatePassword']);
	//头像修改
	$router->post('/User.uploadHeadImg', ['uses' => 'CommUserInfoController@uploadHeadImg', 'as' => 'User.uploadHeadImg']);

//消息
	//获取消息列表
	$router->post('/Msg.getMessageList', ['uses' => 'UserMessageController@getMessageList', 'as' => 'Msg.getMessageList']);
	//获取消息详情
	$router->post('/Msg.getMsgContent', ['uses' => 'UserMessageController@getMsgContent', 'as' => 'Msg.getMsgContent']);
//实名认证
	$router->post('/Auth.submitAuthInfo', ['uses' => 'InfoAuthenticationController@submitAuthInfo', 'as' => 'Auth.submitAuthInfo']);
	//判断是否实名认证
	$router->post('/Auth.judgeAuthInfo', ['uses' => 'InfoAuthenticationController@judgeAuthInfo', 'as' => 'Auth.judgeAuthInfo']);

//账户 资产
	//用户账单查询接口
	$router->post('/Bill.getBillInfo', ['uses' => 'BillController@getBillInfo', 'as' => 'Bill.getBillInfo']);
	//用户资产
	$router->post('/Bill.getBalance', ['uses' => 'BillController@getBalance', 'as' => 'Bill.getBalance']);
	//提现页面数据
	$router->post('/Bill.getCash', ['uses' => 'BillController@getCash', 'as' => 'Bill.getCash']);
	//查询流水  升级账单明细
	$router->post('/Bill.getListOrder', ['uses' => 'BillController@getListOrder', 'as' => 'Bill.getListOrder']);
	//获取个人中心小部件
	$router->post('/Bill.getPersonalWidget', ['uses' => 'BillController@getPersonalWidget', 'as' => 'Bill.getPersonalWidget']);
	//用户提现
	$router->post('/Bill.submitCashInfo', ['uses' => 'BillController@submitCashInfo', 'as' => 'Bill.submitCashInfo']);

//团队
	//查询我的推广
	$router->post('/Team.getMyPromotion', ['uses' => 'TeamController@getMyPromotion', 'as' => 'Team.getMyPromotion']);
	//我的团队等级列表
	$router->post('/Team.getListLevel', ['uses' => 'TeamController@getListLevel', 'as' => 'Team.getListLevel']);
	//团队用户,XX等级的直推用户
	$router->post('/Team.getTeamListUser', ['uses' => 'TeamController@getTeamListUser', 'as' => 'Team.getTeamListUser']);
	//获取直推上级
	$router->post('/Team.judgeRecommendRelition', ['uses' => 'TeamController@judgeRecommendRelition', 'as' => 'Team.judgeRecommendRelition']);
	//团队使用邀请码切换
	$router->post('/Team.switchTeamRelations', ['uses' => 'TeamController@switchTeamRelations', 'as' => 'Team.switchTeamRelations']);
	//查询我的直推上级用户,无推荐关系查询为空
	$router->post('/Team.getSuperiorParent1', ['uses' => 'TeamController@getSuperiorParent1', 'as' => 'Team.getSuperiorParent1']);
	//查询推广人数
	$router->post('/Team.getMyPromotionCount', ['uses' => 'TeamController@getMyPromotionCount', 'as' => 'Team.getMyPromotionCount']);

//反馈信息
	//    $router->post('/Feedback.setFeedbackInfo', ['uses' => 'FeedbackMessageController@setFeedbackInfo','as' => 'Feedback.setFeedbackInfo']);

//用户升级
	$router->post('/Upgrade.plusUpgrade', ['uses' => 'UserUpgradeController@plusUpgrade', 'as' => 'Upgrade.plusUpgrade']);
	$router->post('/AgentUpgrade.AgentUpgrade', ['uses' => 'AgentUpgradeController@AgentUpgrade', 'as' => 'AgentUpgrade.AgentUpgrade']);

//邀请码
	//使用邀请码
	$router->post('/Invite.useCode', ['uses' => 'InviteCodeController@useCode', 'as' => 'Invite.useCode']);
	//邀请码转增
	$router->post('/Invite.giveInviteCode', ['uses' => 'InviteCodeController@giveInviteCode', 'as' => 'Invite.giveInviteCode']);
	//获取邀请码页面
	$router->post('/Invite.giveInviteList', ['uses' => 'InviteCodeController@giveInviteList', 'as' => 'Invite.giveInviteList']);

	//获取原六个车用户信息
	$router->post('/OldUser.getOldUserInfo', ['uses' => 'InviteCodeController@getOldUserInfo', 'as' => 'OldUser.getOldUserInfo']);

// 购物车
	// 获取购物车
	$router->post("/ShoppingCar.getMyGoodsCar", ['uses' => 'ShoppingCarController@getMyGoodsCar']);
	// 添加商品到购物车
	$router->post("/ShoppingCar.addGoodsToCar", ['uses' => 'ShoppingCarController@addGoodsToCar']);
	// 更新购物车
	$router->post("/ShoppingCar.updateGoodsCar", ['uses' => 'ShoppingCarController@updateGoodsCar']);
	// 删除商品到购物车
	$router->post("/ShoppingCar.delGoodsCar", ['uses' => 'ShoppingCarController@delGoodsCar']);

//商品订单
	//商品订单生成
	$router->post('/GoodsOrder.generateGoodsOrder', ['uses' => 'GoodsOrderController@generateGoodsOrder', 'as' => 'GoodsOrder.generateGoodsOrder']);
	//批量商品订单生成
	$router->post('/GoodsOrder.batchGenerateGoodsOrder', ['uses' => 'GoodsOrderController@batchGenerateGoodsOrder', 'as' => 'GoodsOrder.batchGenerateGoodsOrder']);
	//订单列表
	$router->post('/GoodsOrder.getGoodsOrder', ['uses' => 'GoodsOrderController@getGoodsOrder', 'as' => 'GoodsOrder.getGoodsOrder']);
	//订单支付
	$router->post('/GoodsOrder.payGoodsOrder', ['uses' => 'GoodsOrderController@payGoodsOrder', 'as' => 'GoodsOrder.payGoodsOrder']);
	//批量订单支付
	$router->post('/GoodsOrder.batchPayGoodsOrder', ['uses' => 'GoodsOrderController@batchPayGoodsOrder', 'as' => 'GoodsOrder.batchPayGoodsOrder']);
	//确认收货
	$router->post('/GoodsOrder.confirmGoods', ['uses' => 'GoodsOrderController@confirmGoods', 'as' => 'GoodsOrder.confirmGoods']);
	//订单删除
	$router->post('/GoodsOrder.delOrder', ['uses' => 'GoodsOrderController@delOrder', 'as' => 'GoodsOrder.delOrder']);
	//订单详情
	$router->post('/GoodsOrder.getOrderDetail', 'GoodsOrderController@getOrderDetail');
// 收货地址

	// 添加收货地址
	$router->post('/Mall.createRecAddress', 'UserRelatedController@createRecAddress');
	// 删除收货地址，。
	$router->post('/Mall.delRecAddress', 'UserRelatedController@delRecAddress');
	// 修改收货地址
	$router->post('/Mall.updRecAddress', 'UserRelatedController@updRecAddress');
	// 设置默认地址
	$router->post('/Mall.setRecDefault', 'UserRelatedController@setRecDefault');
	// 获取地址信息
	$router->post('/Mall.getRecInfo', 'UserRelatedController@getRecInfo');
	// 获取默认地址
	$router->post('/Mall.getDefAddress', 'UserRelatedController@getDefAddress');
	// 获取收货地址列表
	$router->post('/Mall.getRecAddressList', 'UserRelatedController@getRecAddressList');

	//获取二维码
	$router->post('/Common.getQrcode', ['uses' => 'CommonController@getQrcode', 'as' => 'Common.getQrcode']);

	//积分转赠
	$router->post('/Points.givePoint', ['uses' => 'BuyPointCon@givePoint', 'as' => 'Points.givePoint']);

// 开店
	// 获取当前开店的步骤
	// $router->post('/OpenTheBusiness.Step',"OpenTheBusinessController@Step");
	// 提交申请
	// 交费
	// 以上两步成功后返回当前店铺的名字  管理地址
});

// 权限验证、身份验证
$router->group(['middleware' => ['auth', 'permission']], function () use ($router) {

});
//头条相关接口
//获取头条列表
$router->post('/getTopList', 'TopController@getTopList');
//获取头条详情
$router->post('/getTopInfo', 'TopController@getTopInfo');
//首页头条
$router->post('/getHomeTopList', 'TopController@getHomeTopList');

//获取首页头条图片
$router->post('/getTopImg', 'TopController@getTopImg');

//-----------------------------------------------------------------------

// 获取分享信息
$router->post('/Promote.wxShareInfo', 'ToPromoteCon@wxShareInfo');
// 反馈
$router->post('/General.feedback', 'GeneralCon@feedback');
