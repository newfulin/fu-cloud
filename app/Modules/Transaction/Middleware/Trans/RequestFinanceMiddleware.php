<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Finance;
use Closure;
use Illuminate\Support\Facades\Log;

class RequestFinanceMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.

        $re =  Finance::service('CashierService')
            ->with('code',$request['summaryParams']['acct_req_code'])
            ->with('orderId',$request['summaryId'])
            ->with('detailOrderId',$request['detailId'])
            ->with('transAmount',$request['trans_amt'])
            ->run();
        Log::info('记账结果--------------'.json_encode($re));

        if (!$re) {
            Err('记账错误');
        }

        if ($re!= '0000') {
            Log:info('记账失败-----------');
        }
        $request['finance'] = $re;

        return $next($request);
    }
}
