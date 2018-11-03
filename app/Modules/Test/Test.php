<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 11:29
 */

namespace App\Modules\Test;

use Illuminate\Support\Facades\Facade;

class Test extends Facade{

    public static function getFacadeAccessor(){
        return 'app-test';
    }

}