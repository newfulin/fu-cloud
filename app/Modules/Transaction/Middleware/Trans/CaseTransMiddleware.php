<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AcctUserTariffRateRepository;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use App\Modules\Transaction\Repository\PospChannelInfoMercRepository;
use Closure;
use Illuminate\Support\Facades\Config;

class CaseTransMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {

        $request['merc_type'] = '20';
        //提现类型
//        $this->setDetail('cash_type',DICode('trans','GET_CASH_T1'));
//        //到账时间,人工发起
//        $this->setDetail('receive_time',date("Y-m-d",strtotime("+1 day")));

        // TODO: Implement handle() method.
        $request['receive_time'] = date("Y-m-d",strtotime("+1 day"));
        $request['cash_type'] = config('transaction.CASH_TYPE.get_cash_t1');
        return $next($request);
    }
}