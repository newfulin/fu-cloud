<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 11:09
 */

namespace App\Modules\Access ;

use Illuminate\Support\Facades\Facade;

class Access extends Facade {


    public static function getFacadeAccessor(){
        return 'app-access';
    }
}