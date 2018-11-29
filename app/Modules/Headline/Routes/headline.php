<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 17:04
 */

$router->group([],function()use ($router) {
//头条
    //获取头条列表
//    $router->post('/Headline.getTopList', ['uses' => 'TopController@getTopList', 'as' => 'Headline.getTopList']);
    //获取头条详情
//    $router->post('/Headline.getTopInfo', ['uses' => 'TopController@getTopInfo', 'as' => 'Headline.getTopInfo']);
    //头条分享
//    $router->post('/Headline.getTopShare',['uses' => 'ShareController@getTopShare','as' => 'Headline.getTopShare']);
    //二次分享
//    $router->post('/Headline.webShare',['uses' => 'ShareController@webShare','as' => 'Headline.webShare']);






});

//需要身份验证
$router->group(['middleware' => ['auth']], function () use($router){
//点赞
    //数据点赞
    $router->post('/Click.dataClick', ['uses'=>'ClickCountController@dataClick', 'as' => '/Click.dataClick']);
    //获取点赞数量
    $router->post('/Click.getClickCount',['uses' => 'ClickCountController@getClickCount', 'as' =>'/Click.getClickCount' ]);
    //点赞状态
    $router->post('/Click.judgeDataClick',['uses'=>'ClickCountController@judgeDataClick','as' => '/Click.judgeDataClick']);
    //取消点赞
    $router->post('/Click.cancelDataClick',['uses'=>'ClickCountController@cancelDataClick','as' => '/Click.cancelDataClick']);

}
);