<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 11:29
 */

namespace App\Modules\Test;


use Illuminate\Support\ServiceProvider;

class TestProvider extends ServiceProvider {


    public function register()
    {
        app()->singleton('app-test', function () {
            return app()->make('App\Modules\Test\TestModule');
        });

    }

    public function boot()
    {

        $events = app('events');
        $listen = $this->getListen();
        $this->listen = is_array($listen)?$listen :[];
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

    }


    public function getListen()
    {
        // TODO: Implement getListen() method.
        return [
            'App\Modules\Test\Events\DemoAfterEvent' => [
                'App\Modules\Test\Listeners\RegisterTestListener@handle',
            ],
        ];
    }
}