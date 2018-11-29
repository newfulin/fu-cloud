<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use App\Modules\Finance\Repository\CoffeeConsumeOrderRepository;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CommUserInfoRepository;
use App\Modules\Finance\Repository\MeetingOrderRepository;


/**
 * To 对象
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
        Log::info("To 对象账户信息监测");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        Log::info("<<<<<<<<<<To 对象>>>>>>>...");
        $code = $request['code'];
        if($code == "K0260"  ){
            $request = $this->_getK0260($request);
        }
        return $request;
    }

    /**
     * to 对象
     */
    protected function _getK0260($request){
        $order = $request['order'];
        $to_user_id = $order['to_user_id'];
        $request['touserid'] = $to_user_id;
        if($to_user_id==null ||$to_user_id==""){
            Err("转赠对象不存在:5018");
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