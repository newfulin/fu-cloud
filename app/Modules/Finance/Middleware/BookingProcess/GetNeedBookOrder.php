<?php
namespace App\Modules\Finance\Middleware\BookingProcess;

use log;
use Closure;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;

/**
 * 获得需要记账的明细流水
 */
class GetNeedBookOrder extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        log::info("获得需要记账的明细流水");
        $request = $this->getNeedBookOrder($request);
        return $next($request);
    }
    /**
     * 
     */
    protected function getNeedBookOrder($request)
    {
        $reqCode = $request['reqCode'];
        $batchId = $request['batchId'];
        $ret = $this->repository->getNeedBookOrder($reqCode,$batchId);
        log::info("needBookOrder::----------> ".json_encode($ret));
        if(!$ret){
            log::error("该批次没有可以记账的数据 batchId = {$batchId} , reqCode = {$reqCode} ");
            Err("该批次没有可以记账的数据:9999");
        }
        $i=0;
        $needBookOrder = [];
        foreach($ret as $key => $value){
            $needBookOrder[$i++]= $value;
        }
        $request['needBookOrder'] = $needBookOrder;
        log::info("getNeedBookOrder::success");
        return $request;
    }

}