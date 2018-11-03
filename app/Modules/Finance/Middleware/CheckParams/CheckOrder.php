<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CashOrderRepository;

/**
 * 检查收银流水
 */
class CheckOrder extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(CashOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("检查收银流水");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $orderId = $request['orderId'];
        Log::info("<<<<<<<<<<检查收银流水>>>>>>>...".$orderId);
        $ret = $this->repository->getEntity($orderId);
        Log::info(json_encode($ret));
        if(!$ret){
            Log::error("9900:收银流水不存在");
            Err("收银流水不存在:9900");
        }
        $request['order'] = $ret;
        $request['externOrderId'] = $orderId;

        $policy = $request['code'];
        $acct_req_code = $ret['acct_req_code'];
        if($acct_req_code!=$policy){
            Err("接口请求码与收银流水请求码不一致!:9940");
        }

        Log::info("CheckOrder::success");
        return $request;
    }

}