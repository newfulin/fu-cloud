<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:12
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;
use Illuminate\Support\Facades\Log;
use Iwanli\Wxxcx\Wxxcx;

class WxxCxLoginMiddle extends Middleware
{
    public $wxxcx;
    public $user;
    public function __construct(Wxxcx $wxxcx,CommUserRepo $user){
        $this->wxxcx = $wxxcx;
        $this->user = $user;
    }

    public function handle($request, Closure $next)
    {
        Log::info('小程序登陆---');
        Log::info('小程序登陆--- code | '.$request['code']);
        Log::info('小程序登陆--- iv | '.$request['iv']);
        Log::info('小程序登陆--- encryptedData | '.$request['encryptedData']);

        $request['recommendId'] = "";

        //解密数据  WxxcxController
        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $wxconf = $this->wxxcx->getLoginInfo($request['code']);

        Log::info('根据 code 获取用户信息 | '.json_encode($wxconf));
        //获取解密后的用户信息
        $wxinfo = $this->wxxcx->getUserInfo($request['encryptedData'], $request['iv']);
//        dd($wxinfo);
        if(isset($wxinfo['code'])){
            Log::info('错误代码 ' .$wxinfo['code'] . ' | 错误信息 |' .$wxinfo['message']);
            Err('微信授权失败');
        }

        $wxinfo = array_change_key_case(get_object_vars(json_decode($wxinfo)));
//dd($wxinfo);
        Log::info('用户微信信息 | '.json_encode($wxinfo));

        $ret = $this->user->getUserInfoByOpenId($wxinfo);
        if(!$ret){
            //使用获取微信用户信息,并注册
            Log::info('微信用户注册 | '.$wxinfo['openid']);
            //微信小程序  获取到的数据 没有 unionid 默认为空
            $wxinfo['unionid'] = '';
            $ret = Access::service('WxUserRegisterService')
                ->with('wxinfo',$wxinfo)
                ->with('recommendId',$request['recommendId'])
                ->run();
            Log::info('微信用户注册成功 token返回| '.$wxinfo['openid']);
            $token = Token()
                ->setId($ret['user_id'])
                ->setName('')
                ->setRole($ret['user_tariff_code'])
                ->getToken();

            Log::info('使用获取微信用户信息,并注册token===='.json_encode($token));

            return $next($token);
        }
        $token = Token()
            ->setId($ret['id'])
            ->setName('')
            ->setRole($ret['user_tariff_code'])
            ->getToken();
//        Log::info('$token===='.json_encode($token));

        return $next($token);

    }
}