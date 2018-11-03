<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use App\Modules\Finance\Repository\CoffeeConsumeOrderRepository;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CommUserInfoRepository;
use App\Modules\Finance\Repository\MeetingOrderRepository;


/**
 * 收款账户信息监测
 */
class CheckToUser extends Middleware{

    public $repository;
    public $cdRepo;

    /**
     * 注入Repository
     */
    public function  __construct(CommUserInfoRepository $Repository){
        $this->repository = $Repository;
        //$this->cdRepo = $cdRepo;
    }

    public function handle($request, Closure $next)
    {
        Log::info("收款账户信息监测");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        Log::info("<<<<<<<<<<收款账户信息监测>>>>>>>...");
        $code = $request['code'];
        if($code == "K0220" || $code == "K0210" ){
            $request = $this->_getK0220($request);
        }
        if($code == "K0310"){
            $request = $this->_getK0310($request);
        }
        return $request;
    }

    /**
     * 咖啡二维码支付
     */
    protected function _getK0220($request){
        $order = $request['order'];
        $relationId = $order['relation_id'];
        $retCC = app()->make(CoffeeConsumeOrderRepository::class)->getEntity($relationId);

        if($retCC){
            Log::info("<<<<<<<<<<咖啡交易明细流水监测>>>>>>>...");
            $toUserId = $retCC['to_user_id'];
            $ret = $this->repository->getEntity($toUserId);
            if(!$ret){
                Log::error("9904:收款账户信息信息不存在");
                Err("收款账户信息信息不存在:9904");
            }
            $request['touserid'] = $toUserId;
        }else{
            Log::error("9998:咖啡交易明细流水不存在!");
            Err("咖啡交易明细流水不存在:9998");
        }
        return $request;
    }

    /**
     * 会议支付的店长收款方
     */
    protected function _getK0310($request){
        Log::info("<<<<<<<<<<会议支付的店长收款方>>>>>>>...");
        $order = $request['order'];
        $relationId = $order['relation_id'];
        $retCC = app()->make(MeetingOrderRepository::class)->getEntity($relationId);
        if($retCC){
            Log::info("<<<<<<<<<<会议明细流水监测>>>>>>>...");
            $toUserId = $retCC['to_user_id'];
            $ret = $this->repository->getEntity($toUserId);
            if(!$ret){
                Log::error("9804:收款账户信息信息不存在");
                Err("收款账户信息信息不存在:9804");
            }
            $request['touserid'] = $toUserId;
        }else{
            Log::error("9898:咖啡交易明细流水不存在!");
            Err("咖啡交易明细流水不存在:9898");
        }
        return $request;
    }

    /**
     * 获取指定级别合作商
     */
    protected function getLevelUserInfo($retUserInfo,$level)
    {
        foreach ($retUserInfo as $key => $userInfo ){
            $user_tariff_code = $userInfo['user_tariff_code'];
            if($user_tariff_code == $level){
                return $userInfo;
            }
        }
        return array('user_id'=>'0','user_tariff_code'=>$level);
    }

}