<?php
/**
 * Created by VSCode.
 * User: satsun
 * Date: 2018/2/9
 * Time: 17:35
 */

namespace App\Modules\Finance\Bean\Amountbean;

use Illuminate\Support\Facades\Log;

/**
 * 获取交易金额
 */
class TransAmount {

    public function handle($request)
    {
        Log::debug("TransAmount.handle...");
        $order = $request['order'];
        $transAmount = $order['trans_amt'];
        return $transAmount;
    }

}