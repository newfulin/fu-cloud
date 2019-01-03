<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 11:09
 */

namespace App\Modules\Access ;

use Illuminate\Support\ServiceProvider;

class AccessProvider extends ServiceProvider {


    public function register()
    {
        app()->singleton('app-access', function () {
            return app()->make('App\Modules\Access\AccessModule');
        });

    }

    public function boot()
    {
        app()->configure('access');
        app()->configure('const_user');
        app()->configure('const_response');
        app()->configure('common');
        app()->configure('const_param');
        app()->configure('dict');
        app()->configure('const_bank');
        app()->configure('const_sms');
        app()->configure('jpush');
        app()->configure('const_share');
        app()->configure('const_widget');
        app()->configure('const_act');
        app()->configure('const_headline');
        app()->configure('agent');
        app()->configure('wxxcx');
        app()->configure('goods_classify');
    }
}