<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckMoneyMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.
        //10 邀请码升级 20 线下缴费升级
        if($request['type'] == '20'){
            Log::info($request['business_code'].'金额检测'.$request['trans_amt']);
            if ($request['trans_amt'] == config('interface.MONEY.'.$request['business_code'])) {
                return $next($request);
            } else {
                Log::info($request['business_code'].'交易金额错误'.$request['trans_amt']);
                Err('审批金额错误，请重新核实','7777');
            }
        }
        return $next($request);
    }
}