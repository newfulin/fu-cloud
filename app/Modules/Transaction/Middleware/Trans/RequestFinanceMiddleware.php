<?php
namespace App\Modules\Transaction\Middleware;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Finance;
use Closure;

class requestFinanceMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.
//        $re =  Finance::service('CashierService')
//            ->with('code',$request->input('code'))
//            ->with('orderId',$request->input('orderId'))
//            ->with('detailOrderId',$request->input('detailOrderId'))
//            ->with('transAmount',$request->input('transAmount'))
//            ->run();
//                $request['re'] = $re;
        $request['request'] = 'Finance';
        return $next($request);
    }
}
