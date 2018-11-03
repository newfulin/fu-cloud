<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 09:24
 */


namespace App\Listeners;

use App\Events\UserRegisterEvent;
use App\Modules\Access\Repository\CommUserRepo;

class RegisterAccountListener {

    public function __construct()
    {

    }


    public function handle(UserRegisterEvent $event)
    {

        return '0000';

    }

    public function onRegister(UserRegisterEvent $event)
    {
        //$event->object->LEVEL_ID = "22222222222222";
        //$con  = app('redis')->connections();
        //echo json_encode($con);
        echo "***********************************************************";
        //echo "CashierServiceRedis::".app('redis')->zrange('test',0,time());
        Log::info('这里是异步消息');
        return '0000';

    }


}