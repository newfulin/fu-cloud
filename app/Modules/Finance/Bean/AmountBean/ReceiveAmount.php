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
 * 获取到账金额
 */
class ReceiveAmount {

    public function handle($request)
    {
        Log::debug("ReceiveAmount.handle...");
        $receiveAmt = $request['receiveAmt'];
        return $receiveAmt;
    }

}