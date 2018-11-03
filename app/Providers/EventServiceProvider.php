<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegisterEvent' => [
//            'App\Listeners\RegisterAccountListener',
            'App\Listeners\RegisterRewardListener@onRegister'
        ],
        'Illuminate\Database\Events\QueryExecuted' => [
            'App\Listeners\QueryListener'
        ]
    ];




//    /**
//     * 要注册的订阅者
//     *
//     * @var array
//     */
//    protected $subscribe = [
//        'App\Listeners\ExampleSubscriber',
//    ];

}
