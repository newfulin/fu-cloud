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
 * 会议企业利润 总部获取 30% 会议利润
 */
class BusinessProfitAmount {

    public function handle($request)
    {
        Log::debug("PromotionAmount.handle...");
        $businessProfitAmount = $request['busi_prof_amount'];
        return $businessProfitAmount;
    }

}