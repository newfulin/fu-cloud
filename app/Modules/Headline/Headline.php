<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 16:50
 */

namespace App\Modules\Headline;


use Illuminate\Support\Facades\Facade;

class Headline extends Facade
{
    public static function getFacadeAccessor()
    {
        return "app-headline";
    }
}