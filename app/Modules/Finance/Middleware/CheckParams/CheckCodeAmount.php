<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\MeetingOrderRepository;


/**
 * 检查数据字典配置金额
 */
class CheckCodeAmount extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(){
         //$this->repository = $Repository;
    }
    
    public function handle($request, Closure $next)
    {
        Log::info("检查数据字典配置金额");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        //Log::info(json_encode($request));
        $policy = $request['code'];
        $fun = "check".$policy;
        $request = $this->$fun($request);
        return $request;
    }

    /**
     * 扫码微信店主所得,70%...
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    protected function checkK0220($request){
        Log::info("扫码微信店主所得,70%...!");
        $transAmount = $request['transAmount'];//交易金额,提现金额
        $policy = $request['code'];
        $retInfo = getConfigure("CodeAmount",$policy);
        $codeAmount = Money()->calc($transAmount,"*",$retInfo['property2']/100);
        Log::info('CodeAmount:|'.$codeAmount);
        if(Money()->calc($codeAmount,"-", $transAmount)>0){
            Err("交易金额小于分润金额:9401");
        }
        $request['code_amount'] = $codeAmount;
        return $request;
    }

    /**
     * 会议服务费店主所得,meeting * 70%
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    protected function checkK0310($request){
        Log::info(" 会议服务费店主所得,meeting * 70% !");
        $order = $request['order'];
        $relationId = $order['relation_id'];
        $retMeetingOrder = app()->make(MeetingOrderRepository::class)->getEntity($relationId);
        Log::info(json_encode($retMeetingOrder));
        $service_amt = $retMeetingOrder['service_amt'];//会议服务费
        $policy = $request['code'];
        $retInfo = getConfigure("CodeAmount",$policy);
        $codeAmount = Money()->calc($service_amt,"*",$retInfo['property2']/100);
        Log::info('CodeAmount:(service_amt)|'.$codeAmount);
        $businessProfitAmount = Money()->calc($service_amt,"-",$codeAmount);
        $request['code_amount'] = $codeAmount;//店主获取70% 会议利润
        $request['busi_prof_amount'] = $businessProfitAmount;//总部获取 30% 会议利润
        return $request;
    }
}