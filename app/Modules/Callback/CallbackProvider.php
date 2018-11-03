<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:04
 */
namespace App\Modules\Callback ;

use Illuminate\Support\ServiceProvider;

class CallbackProvider extends ServiceProvider {


    public function register()
    {
        app()->singleton('app-callback', function () {
            return app()->make('App\Modules\Callback\CallbackModule');
        });
    }


    public function boot()
    {
        app()->configure('callback');
        app()->configure('interface');

    }
}