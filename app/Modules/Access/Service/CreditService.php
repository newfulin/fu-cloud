<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 14:30
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;
use App\Modules\Transaction\Trans;

class CreditService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }
    //积分转赠
    public function givePoint(CommUserRepo $user,AcctAccountBalanceRepository $acct,$request){
        //获取用户信息
        $userInfo = $user->getUserById($request['user_id']);

        //获取 To 用户信息
        $toUserInfo = $user->getUserByLoginName($request['mobile']);
        if(!$toUserInfo){
            Err('手机号用户不存在!');
        }

        $balance = $acct->getBalance($request['user_id'], '50');
        if($balance < $request['number']) Err('积分余额不足!');

        return Trans::service('ChannelTrans')
            ->with('business_code','A0260')
            ->with('trans_amt',$request['number'])  //积分数量
            ->with('tariff_code',$userInfo['user_tariff_code'])
            ->with('user_id',$request['user_id'])
            ->with('to_user_id',$toUserInfo['user_id'])
            ->with('time',time())
            ->run('givePoint');
    }
}