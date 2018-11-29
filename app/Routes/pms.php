<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:01
 */

$router->group(['middleware' => []], function () use ($router) {
    //用户升级 店长
    $router->post('/PmsUserUpgrade.pmsUserUpgrade', ['uses' => 'PmsUserUpgradeController@pmsUserUpgrade','as' => 'PmsUserUpgrade.pmsUserUpgrade']);
    //升级 区代
    $router->post('/PmsUserUpgrade.pmsAreaUserUpgrade', ['uses' => 'PmsUserUpgradeController@pmsAreaUserUpgrade','as' => 'PmsUserUpgrade.pmsAreaUserUpgrade']);

    //用户自动补发邀请码
    $router->post('/Examine.SendInvCode', 'UpgradeToExamineController@SendInvCode');
    //升级 审核
    $router->post('/Examine.upgradeAudit', ['uses' => 'UpgradeToExamineController@upgradeAudit','as' => 'Examine.upgradeAudit']);
    //邀请码升级审核
    $router->post('/Examine.InviteCodeUpgradeAudit', ['uses' => 'UpgradeToExamineController@InviteCodeUpgradeAudit','as' => 'Examine.InviteCodeUpgradeAudit']);

    //六个车合伙人升级PMS生成外部的邀请码
    $router->post('/PmsInviteCode.pmsPartnerInviteCode',['uses' => 'PmsInviteCodeController@pmsPartnerInviteCode','as' => 'PmsInviteCode.pmsPartnerInviteCode']);
    //六个车合作商，升级PMS生成外部的邀请码
    $router->post('/PmsInviteCode.pmsOperatorInviteCode',['uses' => 'PmsInviteCodeController@pmsOperatorInviteCode','as' => 'PmsInviteCode.pmsOperatorInviteCode']);
    //六个车合作商，车巢升级PMS生成外部的邀请码
    $router->post('/PmsInviteCode.pmsCarNestInviteCode',['uses' => 'PmsInviteCodeController@pmsCarNestInviteCode','as' => 'PmsInviteCode.pmsCarNestInviteCode']);
//推送
    //用户单个推送 PMS
    $router->post('/JPush.singlePushPms', ['uses' => 'JPushController@singlePushPms','as' => 'JPush.singlePushPms']);
    //根据用户等级推送
    $router->post('/JPush.sendJPushMsg', ['uses' => 'JPushController@sendJPushMsg','as' => 'JPush.sendJPushMsg']);
    //消息广播,所有用户
    $router->post('/JPush.sendAllJPushMsg', ['uses' => 'JPushController@sendAllJPushMsg','as' => 'JPush.sendAllJPushMsg']);

//    //RD贝系统账户充值 A0810
//    $router->post('/DataInitial.RDSystemRecharge', ['uses' => 'DataInitialController@RDSystemRecharge','as' => 'DataInitial.RDSystemRecharge']);
//    //活动总负债转账
//    $router->post('/DataInitial.ActivityLiabilitiesTA', ['uses' => 'DataInitialController@ActivityLiabilitiesTA','as' => 'DataInitial.ActivityLiabilitiesTA']);
//    //企业预留负债转账800w
//    $router->post('/DataInitial.EnterpriseLiabilitiesTA', ['uses' => 'DataInitialController@EnterpriseLiabilitiesTA','as' => 'DataInitial.EnterpriseLiabilitiesTA']);
//    //员工预留负债转账200w
//    $router->post('/DataInitial.StaffLiabilitiesTA', ['uses' => 'DataInitialController@StaffLiabilitiesTA','as' => 'DataInitial.StaffLiabilitiesTA']);
});
