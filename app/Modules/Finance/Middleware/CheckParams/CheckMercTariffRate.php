<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use App\Common\Models\TranOrder;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Repository\AcctUserTariffRateRepository;

/**
 * 用户资费检查
 */
class CheckMercTariffRate extends Middleware
{

    public $repository;

    /**
     * 注入Repository
     */
    public function __construct(AcctUserTariffRateRepository $Repository)
    {
        $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("资费检查");
        $request = $this->checkParams($request);
        return $next($request);
    }
    /**
     * 检测用户资费,同事检测到账金额跟算法是否一致
     */
    protected function checkParams($request)
    {
        $order = $request['order'];
        $transAmount = $request['transAmount'];
        //获取用户资费
        $ret = $this->repository->getRateInfo($order['business_code'], $order['merc_tariff_code']);
        if(!$ret){
            Err("用户资费不存在!!:5040");
        }
        Log::info("用户资费标准>>>>");
        Log::info("businessCode:|" . $order['business_code']."|user_tariff_code:|" . $order['merc_tariff_code']);
        Log::info("rate:|" . $ret['rate']."|max_rate:|" . $ret['max_rate']);
        Log::info("base_rate:|" . $ret['base_rate']."|base_max_rate:|" . $ret['base_max_rate']);
        //用户资费标准>>>>
        $request['rateInfo'] = ['rate' => $ret['rate'], 'max_rate' => $ret['max_rate'], 
        'base_rate' => $ret['base_rate'], 'base_max_rate' => $ret['base_max_rate']];
        //计算到账金额
        $ret = Money()->getReceiveAmt($request['rateInfo'],$transAmount);
        $receiveAmt = $ret['receiveAmt'];
        $fee = $ret['fee'];
        $request['fee']=$fee;
        $request['receiveAmt'] = $receiveAmt;
        Log::info("到账金::".$request['receiveAmt']."手续费::".$request['fee']);
        if (!Money()->calc($receiveAmt,"==",$order['receive_amt'])) {
            Log::error($receiveAmt."!=".$order['receive_amt']);
            Err("RECEIVE_AMT_ERROR");
        }
        Log::info("CheckMercTariffRate::success");
        return $request;
    }

}