<?php

namespace App\Modules\Transaction;


use Illuminate\Support\ServiceProvider;

class TransactionProvider extends ServiceProvider
{


    public function register()
    {
        app()->singleton('app-transaction', function () {
            return app()->make('App\Modules\Transaction\TransactionModule');
        });

    }

    public function boot()
    {
        app()->configure('transaction');
        app()->configure('interface');
        app()->configure('parameter');
    }






}