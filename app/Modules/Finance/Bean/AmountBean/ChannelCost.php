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
 * 获取通道成本
 */
class ChannelCost {


    public function handle($request)
    {
        Log::debug("ChannelCost.handle...");
        $costAmt = $request['channelCost']['cost_amt'];
        return $costAmt;
    }

}