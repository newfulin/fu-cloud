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
 * 现金积分转换 1:5
 */
class PortionAmount {

    public function handle($request)
    {
        Log::debug("PortionAmount.handle...");
        $order = $request['order'];
        $transAmount =  Money()->calc($order['trans_amt'],"*",5);
        return $transAmount;
    }

}