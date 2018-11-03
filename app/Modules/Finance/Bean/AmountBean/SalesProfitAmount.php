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
 * 分润
 */
class SalesProfitAmount {

    public function handle($request)
    {
        Log::debug("SalesProfitAmount.handle...");
        return '0.00';
    }

}