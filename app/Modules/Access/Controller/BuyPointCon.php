<?php

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\WxUserInfoRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Modules\Transaction\Trans;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;


class BuyPointCon extends Controller
{

    public function getRules()
    {
        return [
            'buyPoint' => [
                'amount' => 'required|desc:金额',
            ],
            'givePoint' => [
                'mobile' => 'required|mobile|desc:转赠手机号',
                'number' => 'required|desc:转赠数量',
            ]
        ];
    }

    /**
     * @desc 我的积分充值界面
     */
    public function myPointBalance(AcctAccountBalanceRepository $balance, Request $request)
    {
        $userId = $request->user()->claims->getId();
        $re['balance'] = $balance->getBalance($userId, '50');
        $re['buy'] = [
            ['money' => '100', 'point' => '500'],
            ['money' => '200', 'point' => '1000'],
        ];
        return $re;
    }

    /**
     * @desc 积分充值
     */
    public function buyPoint(CommUserInfoRepository $user, WxUserInfoRepo $wx, Request $request)
    {
        $userId = $request->user()->claims->getId();
        $level = $user->getUserLevel($userId);
        $wxUser = $wx->getWxInfo($userId);
        $ret = Trans::service('ChannelTrans')
            ->with('business_code', 'A0200')
            ->with('trans_amt', $request->input('amount'))
            ->with('tariff_code', $level)
            ->with('open_id', $wxUser['openid'])
            ->with('user_id', $userId)
            ->with('time', time())
            ->run('buyPoint');
        return $ret;
    }

    /**
     * @desc 积分赠送
     */
    public function givePoint(Request $request){
        $user_id = $request->user()->claims->getId();
        return Access::service('CreditService')
            ->with('user_id',$user_id)
            ->with('number',$request->input('number'))
            ->with('mobile',$request->input('mobile'))
            ->run('givePoint');
    }
}
