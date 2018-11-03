<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use App\Common\Models\TranOrder;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;

/**
 * 金额检查
 */
class CheckAmount extends Middleware{

    public function handle($request, Closure $next)
    {
        Log::info("金额检查");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $order = $request['order'];
        $transAmount = $request['transAmount'];
        if ($order['status'] == Config::get('finance.trans.STATUS_FAIL.code')) {
            Log::error("收银状态:|".Config::get('finance.trans.STATUS_FAIL.msg'));
            Err("收银状态错误:9901");
        }
        if (!Money()->calc($order['trans_amt'],"==",$transAmount)) {
            //有差额
            Err("收银流水的交易金额与请求记账的交易金额不一致:9902");
        }
        // 判断到账金额不能大于交易金额
        if (Money()->calc($order['receive_amt'],"-",$transAmount)>0) {
            Log::error($order['receive_amt']."金额检查".$transAmount);
            Err("到账金额有误:9903");
        }
        Log::info("CheckAmount::success");
        return $request;
    }

}