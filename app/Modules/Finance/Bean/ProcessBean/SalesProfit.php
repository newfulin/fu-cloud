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
 * 营销模式账户!!
 */
class SalesProfit {

    public function handle($request)
    {
        Log::debug("getProcessId.SalesProfit.handle...");
        $template = $request['book']['template'];
        return '1';
    }
}