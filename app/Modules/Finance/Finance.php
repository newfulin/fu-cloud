<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 09:58
 */

namespace App\Modules\Finance;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Facade;

class Finance extends Facade {

    public static function getFacadeAccessor(){
        Log::debug("Finance.getFacadeAccessor.app-finance...");
        return 'app-finance';
    }

}