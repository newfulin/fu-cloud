<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 16:55
 */

namespace App\Modules\Headline;


use Illuminate\Support\ServiceProvider;

class HeadlineProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('app-headline', function(){
            return app()->make('App\Modules\Headline\HeadlineModule');
        });
    }
    public function boot(){
        app()->configure('const_share');
        app()->configure('parameter');

    }
}