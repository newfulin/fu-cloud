<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 8:57
 */

namespace App\Modules\Pms;


use Illuminate\Support\ServiceProvider;

class PmsProvider extends ServiceProvider
{
    public function register(){
        app()->singleton('app-pms', function () {
            return app()->make('App\Modules\Pms\PmsModule');
        });
    }
}