<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\TranTransOrderRepository;

/**
 * 检查交易明细流水
 */
class CheckDetail extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(TranTransOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("检查交易明细流水");
        //Log::info(json_encode($request));
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $detailOrderId = $request['detailOrderId'];
        Log::info("<<<<<<<<<<检查交易明细流水>>>>>>>...".$detailOrderId);
        $ret = $this->repository->getEntity($detailOrderId);
        //Log::info(json_encode($ret));
        if(!$ret){
            Log::error("9999:明细流水不存在");
            Err("明细流水不存在:9999");
        }
        //交易流水财务请求码
        $acct_req_code = $ret['acct_req_code'];
        $reqCode = $request['code'];
        if($reqCode!=$acct_req_code){
            Err("财务请求码与交易明细标定请求码不一致:9999");
        }
        $request['detailOrder'] = $ret;
        Log::info("CheckDetail::success");
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //原记账方式,将明细流水ID作为记账流水关联ID
        $request['externOrderId'] = $request['detailOrderId'];
        return $request;
    }

}