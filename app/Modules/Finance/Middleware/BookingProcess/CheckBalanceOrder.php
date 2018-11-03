<?php
namespace App\Modules\Finance\Middleware\BookingProcess;

use log;
use Closure;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;

/**
 * 记账凭证的试算平衡
 */
class CheckBalanceOrder extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        log::info("记账凭证的试算平衡");
        $request = $this->checkBalanceOrder($request);
        return $next($request);
    }
    /**
     * 记账凭证的试算平衡
     * @desc 记账凭证的试算平衡 
     */
    protected function checkBalanceOrder($request)
    {
        $needBookOrder = $request['needBookOrder'];
        $total_debit = '0.00';
        $total_credit = '0.00';
        foreach($needBookOrder as $key=>$val){
            $total_debit = Money()->calc($total_debit,'+',$val['debit_amount']);
            $total_credit = Money()->calc($total_credit,'+',$val['credit_amount']);
        }
        $amount = $total_debit - $total_credit;
        $amount = Money()->format($amount);
        if(!Money()->calc($total_debit,"==",$total_credit)){
            log::error("该批次记账凭证不平衡 batchId = {$batchId} , reqCode = {$reqCode} ");
            Err('该批次记账凭证不平衡,放弃更新余额:9999');
        }
        log::info("checkBalanceOrder::success");
        return $request;
    }

}