<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CashOrderRepository;

/**
 * 检查推广金额
 */
class CheckPromotionAmount extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(CashOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("检查推广金额");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $orderId = $request['orderId'];
        $promotionAmount = $request['transAmount'];
        Log::info("<<<<<<<<<<检查推广金额>>>>>>>...".$orderId);
        $ret = $this->repository->getEntity($orderId);
        //Log::info(json_encode($ret));
        if(!$ret){
            Log::error("9999:记账单不存在");
            Err("记账单不存在:9999");
        }
        $request['order'] = $ret;
        $transAmount = $ret['trans_amt'];
        if (!Money()->calc($promotionAmount,"==",$transAmount)) {
            Err("检查推广金额不一致:9999");
        }
        $request['externOrderId'] = $orderId;
        Log::info("CheckPromotionAmount::success");
        return $request;
    }

}