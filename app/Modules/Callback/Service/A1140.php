<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:45
 */

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\InviteCodeRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\Log;

class A1140 extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //更新流水
    public function update(TranTransOrderRepo $order,CommUserRepo $user,InviteCodeRepo $code,$request){
        Log::info(' 原六个车合伙人转为区代 | '.$request['detailId']);
        $order->update($request['detailId'],$request['params']);

        //更新订单支付
        $orderInfo = $order->getDetailOrder($request['detailId']);

        //更新用户等级
        $data = [
            'user_tariff_code' => config('const_user.AREA_USER.code'),
            'level_name' => config('const_user.AREA_USER.code')
        ];
        $user->updateUser($orderInfo['user_id'],$data);

        //更新邀请码
        $code->updateState($orderInfo['invite_code'],
            [
                'state' => '20',
                'use_user_id' => $orderInfo['user_id'],
            ]);
    }
}