<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 10:21
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;

class CheckMobileRepeatMiddle extends Middleware
{
    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next){
        $userInfo = $this->repo->getUser($request['user_id']);
        //检查当前用户等级,招商经理,招商总监 不可充值
        if(in_array($userInfo['user_tariff_code'],config('const_user.NOTRECHARGE'))){
            Err('此等级不可实名认证,详情请联系客服!',5555);
        }

        if(!empty($request['login_name'])){
            if(!preg_match('/^1[345789]\d{1}\d{8}$/', $request['login_name'])){
                Err('请输入正确的手机号');
            }
            $ret = $this->repo->getUserByLoginName($request['login_name']);
            if($ret) Err('该手机号已存在');
        }
        return $next($request);
    }
}