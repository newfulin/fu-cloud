<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\CommUserInfoRepository;

/**
 * 用户信息验证
 */
class CheckUser extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(CommUserInfoRepository $Repository){
         $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("用户信息验证");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $order = $request['order'];
        Log::info("<<<<<<<<<<用户信息验证>>>>>>>...".$order['user_id']);
        $ret = $this->repository->getEntity($order['user_id']);
        if(!$ret){
            Log::error("9904:用户信息不存在");
            Err("用户信息不存在:9904");
        }
        $request['userinfo'] = $ret ;
        Log::info("CheckUser::success");
        return $request;
    }

}