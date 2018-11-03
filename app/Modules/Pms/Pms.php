<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 8:56
 */

namespace App\Modules\Pms;


use Illuminate\Support\Facades\Facade;

class Pms extends Facade
{
    public static function getFacadeAccessor(){
        return 'app-pms';
    }
}