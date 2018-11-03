<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/5/30
 * Time: 09:16
 */

$router->group(['middleware' => []], function () use ($router) {
    $router->get('/test.cache', 'TestCacheController@index');
    $router->post('/test.updateTest', 'TestCacheController@updateTest');
    $router->post('/test.getToken', 'DemoController@getToken');
    $router->post('/test.cashier', 'FinanceController@cashier');
    $router->post('/test.bookingUpdate', 'BookkeepingUpdateController@bookingkupdate');
    $router->post('/test.testAfter', 'DemoController@testAfter');
    $router->post('/test.testFun', 'DemoController@testFun');
    $router->post('/test.retTest', 'DemoController@retTest');
    //$router->post('/test.signUpMeet', 'TestMeentingController@signUpMeet');
});
