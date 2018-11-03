<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:01
 */

$router->group(['middleware' => []], function () use ($router) {
    //用户升级
    $router->post('/PmsUserUpgrade.pmsUserUpgrade', ['uses' => 'PmsUserUpgradeController@pmsUserUpgrade','as' => 'PmsUserUpgrade.pmsUserUpgrade']);

    //升级 审核
    $router->post('/Examine.upgradeAudit', ['uses' => 'UpgradeToExamineController@upgradeAudit','as' => 'Examine.upgradeAudit']);

//推送
    //用户单个推送 PMS
    $router->post('/JPush.singlePushPms', ['uses' => 'JPushController@singlePushPms','as' => 'JPush.singlePushPms']);
    //根据用户等级推送
    $router->post('/JPush.sendJPushMsg', ['uses' => 'JPushController@sendJPushMsg','as' => 'JPush.sendJPushMsg']);
    //消息广播,所有用户
    $router->post('/JPush.sendAllJPushMsg', ['uses' => 'JPushController@sendAllJPushMsg','as' => 'JPush.sendAllJPushMsg']);
});
