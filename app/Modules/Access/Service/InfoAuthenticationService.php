<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 14:35
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Middleware\CheckAuthCardMiddle;
use App\Modules\Access\Middleware\CheckMobileRepeatMiddle;
use App\Modules\Access\Middleware\RealUserInfoMiddle;
use App\Modules\Access\Repository\CommUserRepo;

class InfoAuthenticationService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        CheckMobileRepeatMiddle::class => [
            'only' => 'submitAuthInfo'
        ],
        CheckAuthCardMiddle::class => [
            'only' => 'submitAuthInfo'
        ],
        RealUserInfoMiddle::class => [
            'only' => 'submitAuthInfo'
        ]
    ];

    //实名认证
    public function submitAuthInfo($request)
    {
        if($request['data'] != 1) Err('操作失败:1001');
    }

    public function judgeAuthInfo(CommUserRepo $user,$request){
        $userInfo = $user->getUser($request['user_id']);
        //检查当前用户等级,招商经理,招商总监 不可充值
        if(in_array($userInfo['user_tariff_code'],config('const_user.NOTRECHARGE'))){
            Err('此等级不可实名认证,详情请联系客服!',5555);
        }
    }
}