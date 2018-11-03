<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 09:59
 */
namespace App\Listeners;

use App\Events\UserRegisterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QueueListener implements ShouldQueue {

    //异步处理,需要配置evn QUEUE_DRIVER
    use InteractsWithQueue;

    public function handle(UserRegisterEvent $event)
    {
        //Finance::Service('siss')->with($event->)
    }

}