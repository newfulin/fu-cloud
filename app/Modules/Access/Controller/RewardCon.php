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
use Illuminate\Http\Request;

class RewardCon extends Controller
{
    public function getRules(){
        return [
            'redPacket' => [
            ],
        ];
    }

    /**
     * @desc 奖励：微信红包
     */
    public function redPacket(Request $request)
    {
        $result = app('nxp-wechat')->RedPacket()
            ->redPacket();
        return $result;


//        return Access::service('ShareService')
//            ->run('getWechatShareInfo');
    }

}