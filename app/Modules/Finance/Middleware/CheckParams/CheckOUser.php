<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CardDetailRepository;
use App\Modules\Finance\Repository\CommUserInfoRepository;

/**
 * 原始卡券所有人ID,用户信息验证
 */
class CheckOUser extends Middleware{

    public $repository;
    public $cdRepo;

    /**
     * 注入Repository
     */
    public function  __construct(CommUserInfoRepository $Repository,CardDetailRepository $cdRepo){
         $this->repository = $Repository;
         $this->cdRepo = $cdRepo;
    }

    public function handle($request, Closure $next)
    {
        Log::info("原始卡券所有人ID,用户信息验证");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $order = $request['order'];
        Log::info("<<<<<<<<<<用户信息验证>>>>>>>...".$order['user_id']);
        $ret = $this->repository->getEntity($order['user_id']);
        if(!$ret){
            Log::error("9999:用户信息不存在");
            Err("用户信息不存在:9999");
        }
        $request['userinfo'] = $ret ;
        Log::info("CheckUser::success");
        $detailOrder = $request['detailOrder'];
        $pay_id = $detailOrder['pay_id'];//关联卡券ID
        $cardDetail = $this->cdRepo->getEntity($pay_id);
        $ouserid =  $cardDetail['ouserid'];
        $retO = $this->repository->getEntity($ouserid);
        if(!$retO){
            Log::error("9999:OO用户信息不存在");
            Err("OO用户信息不存在:9999");
        }
        $request['ouserid'] = $ouserid ;
        return $request;
    }

}