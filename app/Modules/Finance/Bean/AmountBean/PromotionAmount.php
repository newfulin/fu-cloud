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
 * 获取记账单(原汇总流水)金额
 */
class PromotionAmount {

    public function handle($request)
    {
        Log::debug("PromotionAmount.handle...");
        $order = $request['order'];
        $promotionAmount = $order['trans_amt'];
        return $promotionAmount;
    }

}