<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 18:28
 */

namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\WxUserInfoRepo;
use App\Modules\Access\Service\CommonService;
use Closure;
use Illuminate\Support\Facades\Log;

class GetWxInfoMiddle extends Middleware
{
    public $repo;
    public $user;
    public function __construct(WxUserInfoRepo $repo,CommUserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    public function handle($request, Closure $next)
    {
//        $request = $next($request);
        Log::info('创建微信用户信息 ' . $request['code']);
        Log::info('flag ' . $request['flag']);
        //根据openid 获取微信信息
        $wxconf = app('nxp-wechat')->wxInfo()
            ->getOpenId($request['code'],$request['flag']);

        if(isset($wxconf['errcode'])){
            Log::info('错误代码 ' .$wxconf['errcode'] . ' | 错误信息 |' .$wxconf['errmsg']);
            Err('微信授权失败');
        }
        Log::info('$wxconf===='.json_encode($wxconf));
        $wxinfo = app('nxp-wechat')->wxInfo()
            ->getWxInfo($wxconf);

        $ret = $this->user->getUserInfoByUnionid($wxinfo);

        Log::info('$ret===='.json_encode($ret));

        if(!$ret){
            //使用获取微信用户信息,并注册
            Log::info('微信用户注册 | '.$wxinfo['unionid']);

            $ret = Access::service('WxUserRegisterService')
                ->with('wxinfo',$wxinfo)
                ->with('recommendId',$request['recommendId'])
                ->run();
            Log::info('微信用户注册成功 token返回| '.$wxinfo['unionid']);
            $token = Token()
                ->setId($ret['user_id'])
                ->setName('')
                ->setRole($ret['user_tariff_code'])
                ->getToken();

            Log::info('使用获取微信用户信息,并注册token===='.json_encode($token));

            return $next($token);
        }
        $update = [
            'user_name' => app()->make(CommonService::class)->with('str',$wxinfo['nickname'])->run('userTextEncode'),
            'headimgurl' => isset($wxinfo['headimgurl']) ? $wxinfo['headimgurl'] : $wxinfo['avatarurl']
        ];
        $this->user->updateUser($ret['user_id'],$update);
        $token = Token()
            ->setId($ret['id'])
            ->setName('')
            ->setRole($ret['user_tariff_code'])
            ->getToken();
        Log::info('$token===='.json_encode($token));

        return $next($token);
    }

//    //检查推荐用户
//    public function checkRecommend($request){
//        $request['register_type'] = '02';
//        if(!isset($request['recommendId']) || empty($request['recommendId'])){
//            $login_name = config('const_user.RECOMMEND');
//            $userInfo = $this->user->getUserByLoginName($login_name);
//            $request['recommendId'] = $userInfo['user_id'];
//            $request['register_type'] = '01';
//        }
//        return $request;
//    }

}