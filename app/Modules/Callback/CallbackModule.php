<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:07
 */
namespace App\Modules\Callback ;

use App\Common\Contracts\Module;
use App\Modules\Callback\Events\DemoServiceBeforeEvent;
use App\Modules\Callback\Listeners\OneListener;
use App\Modules\Callback\Listeners\QueueListener;

class CallbackModule extends Module{

    public function getListen()
    {
        return [
            DemoServiceBeforeEvent::class =>[
                OneListener::class,
                QueueListener::class
            ]
        ];
    }

    public function getSubscribe()
    {
        // TODO: Implement getSubscribe() method.
    }


}