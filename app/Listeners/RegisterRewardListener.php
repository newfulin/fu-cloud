<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/23
 * Time: 17:54
 */

namespace App\Listeners;

use Exception;
use App\Events\UserRegisterEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegisterRewardListener implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent $event
     * @return void
     */
    public function handle(UserRegisterEvent $event)
    {
        //调用service进行处理
        echo($event->object->id);
        return '0000';
    }

    public function onRegister(UserRegisterEvent $event)
    {

        Cache::put('lumen', 'Hello, Lumen.', 5);
        Log::info('这里是异步消息');
//        echo  Cache::get('lumen');
//
//        try{
//            throw new Exception("zheli shi try 报错");
//        }catch(Exception $ex){
//            Err($ex->getMessage());
//        }
//        Err("123123");
        echo "测试测试";
        Log::info('这里是异步消息');
        echo($event->object->id);
        echo($event->object->user_name);
        //app('redis')->set('CashierServiceRedis','123123');
        //$con  = app('redis')->connections();
        //echo json_encode('-------------------------------');
        //echo json_encode(app('redis')->zrange('test',0,time()));
        Log::info('这里是异步消息');
        Log::info(json_encode($event->object));
        Log::info($event->object->user_name);
        return '0000';
    }


}