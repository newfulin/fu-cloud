<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 17:15
 */
namespace App\Modules\Transaction;


use Illuminate\Support\Facades\Facade;

class Trans extends Facade{

    public static function getFacadeAccessor(){
        return 'app-transaction';
    }


}