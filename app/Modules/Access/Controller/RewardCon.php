<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 9:34
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\WxUserInfoRepo;
use Illuminate\Http\Request;
use App\Modules\Transaction\Trans;
use App\Modules\Access\Repository\CommUserRepo;



class RewardCon extends Controller
{
    public function getRules(){
        return [
            'redPacket' => [
                'businessCode' => 'required',
                'transAmt' => 'required',
                'lock' => 'required',
            ],
        ];
    }

    /**
     * @desc 奖励：微信红包(付款)
     */
    public function redPacket(WxUserInfoRepo $wx ,CommUserRepo $user,Request$request)
    {
        $userId = $request->user()->claims->getId();


//        $userId = '1160859687214836737';

        $user_tariff_code = $user->getUserLevelById($userId);
        if(!$user_tariff_code){
            Err('用户信息获取失败');
        }

        $lock = $request->input('lock');
        if ($lock != 'f0af962ddbc82430e947390b2f3f6e49'){
            Err('验证失败');
        }
//        $openId = 'o2Y6E1b8tA8J10gi3DT2yzVKqLX8';

        $ret = Trans::service('ChannelTrans')
            ->with('business_code',$request->input('businessCode'))
            ->with('trans_amt',$request->input('transAmt'))
            ->with('tariff_code',$user_tariff_code)
            ->with('user_id',$userId)
            ->with('time',time())
            ->run('merchantPay');
        return $ret;




    }

}