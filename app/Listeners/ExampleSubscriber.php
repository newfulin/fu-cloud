<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/19
 * Time: 16:13
 */

namespace App\Listeners;

class ExampleSubscriber
{
    public function onUserLogin($event)
    {
    }

    public function onUserLogout($event)
    {
    }


    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\ExampleSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\ExampleSubscriber@onUserLogout'
        );
    }
}