<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 15:34
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Middleware\GetWxInfoMiddle;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Support\Facades\Log;

class WxLoginService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        //创建微信信息
        GetWxInfoMiddle::class => [
            'only' => 'doLoginProcess'
        ]
    ];

    //微信登陆
    public function doLoginProcess(CommUserRepo $repo,$request){

//        $ret = $repo->getUserInfoByUnionid($request);

//        //根据unionid 获取用户信息
//        if(!$ret){
//            //使用获取微信用户信息,并注册
//            Log::info('微信用户注册 | '.$request['unionid']);
//            $ret = Access::service('WxUserRegisterService')
//                ->with('unionid',$request['unionid'])
//                ->with('openid',$request['openid'])
//                ->run();
//            return $ret;
//        }
        //返回用户信息
//        dd($request);

        return $request;
    }
}