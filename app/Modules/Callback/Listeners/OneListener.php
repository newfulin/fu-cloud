<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:44
 */

namespace App\Modules\Callback\Listeners ;

use Illuminate\Support\Facades\Log;

class OneListener {


    public function handle($request)
    {
        Log::info("OneListener:".json_encode($request));
    }

}