<?php


/*
$router->get('/Callback.Test',
    ['uses' => 'TestController@index', 'as' =>'Callback.Test']
);
*/
$router->post('/Callback', 'WeChatController@NativePay');
$router->post('/Resale', 'WeChatController@Resale');


