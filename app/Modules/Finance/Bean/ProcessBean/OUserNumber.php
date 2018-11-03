<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 16:02
 */

namespace App\Modules\Finance\Bean\ProcessBean;

use Illuminate\Support\Facades\Log;


/**
 * 获取原始卡券所有人ID
 */
class OUserNumber {

    public function handle($request)
    {
        Log::debug("getProcessId.OUserNumber.handle...");
        $ouserid = $request['ouserid'];
        return $ouserid;
    }
}