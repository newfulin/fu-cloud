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
 * 产品成本价
 */
class ProductCostAmount {

    public function handle($request)
    {
        Log::debug("ProductCostAmount.handle...");
        $businessProfitAmount = $request['product_cost_amount'];
        return $businessProfitAmount;
    }

}