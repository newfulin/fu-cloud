<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 18:46
 */
namespace App\Modules\Finance;

class FinanceProvider {
    public function register()
    {
        app()->singleton('app-finance', function () {
            return app()->make('App\Modules\Finance\FinanceModule');
        });

    }

    public function boot()
    {
        app()->configure('finance');
        //app()->configure('const_finance');
        app()->configure('const_account');
    }

}