<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 11:29
 */
namespace App\Modules\Test;

use App\Common\Contracts\Module;
use App\Modules\Test\Events\DemoAfterEvent;
use App\Modules\Test\Listeners\RegisterTestListener;

class TestModule extends Module {

    public function getListen()
    {
        // TODO: Implement getListen() method.
        return [
            DemoAfterEvent::class => [
              RegisterTestListener::class
            ],
//            'App\Modules\Test\Events\DemoAfterEvent' => [
//                'App\Modules\Test\Listeners\RegisterTestListener@handle',
//            ],
        ];
    }

    public function getSubscribe()
    {
        // TODO: Implement getSubscribe() method.
    }


}