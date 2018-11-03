<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 10:29
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;

class JudgeUserLevelMiddle extends Middleware
{
    public function __construct(CommUserRepo $user)
    {
        $this->user = $user;
    }

    public function handle($request, Closure $next)
    {
        $userInfo = $this->user->getUser($request['user_id']);
        if($userInfo['user_tariff_code'] >= $request['upgrade_level']){
            Err('当前等级不可再次升级');
        }
        return $next($request);
    }
}