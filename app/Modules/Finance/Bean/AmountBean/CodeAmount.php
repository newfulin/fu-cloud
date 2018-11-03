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
 * 需要通过数据字典 匹配计算的金额
 */
class CodeAmount {

    public function handle($request)
    {
        Log::debug("PromotionAmount.handle...");
        $codeAmount = $request['code_amount'];
        return $codeAmount;
    }

}