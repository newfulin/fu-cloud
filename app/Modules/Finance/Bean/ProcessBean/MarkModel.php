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
 * 市场模式
 */
class MarkModel {

    public function handle($request)
    {
        Log::debug("getProcessId.MarkModel...");
        $template = $request['book']['template'];
        //先设置一个临时的用户编号
        return '000000';
    }
}