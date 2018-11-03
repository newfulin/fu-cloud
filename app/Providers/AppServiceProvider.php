<?php

namespace App\Providers;

use App\Common\Validation\Validation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //自定义日志配置

        if(app()->runningInConsole()){

            app()->configureMonologUsing(function ($monolog) {
                $handler = new \App\Handlers\ConsoleLoggerHandler();
                return $monolog->pushHandler($handler);
            });

        }else{
            app()->configureMonologUsing(function ($monolog) {
                $handler = new \App\Handlers\LoggerHandler();
                return $monolog->pushHandler(new \Monolog\Handler\BufferHandler($handler));
            });
        }
    }

    public function boot()
    {
        app()->configure('app');
        //debug模式开启sql跟踪
        if (Config::get('app.debug')) {
            app('db')->enableQueryLog();
        }
//        Validator::extend('mobile',function($attribute, $value, $parameters){
//            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
//        });
        Validator::resolver(function($translator, $data, $rules, $messages){
            return new Validation($translator, $data, $rules, $messages);
        });

    }
}
