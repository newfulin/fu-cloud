<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/22
 * Time: 10:26
 */
namespace Nxp\Wechat;

use Illuminate\Support\ServiceProvider;

class WechatServiceProivder extends ServiceProvider {

    public function register()
    {
        app()->singleton('nxp-wechat',function(){
            return app()->make(Wechat::class);
        });
    }

    public function boot()
    {
        app()->configure('parameter');
    }

}