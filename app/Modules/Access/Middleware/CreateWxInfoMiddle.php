<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26
 * Time: 10:24
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\WxUserInfoRepo;
use App\Modules\Access\Service\CommonService;
use Closure;
use Illuminate\Support\Facades\Log;

class CreateWxInfoMiddle extends Middleware
{
    public $repo;
    public $user;
    public function __construct(WxUserInfoRepo $repo,CommUserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }
    public function handle($request, Closure $next){

        $wxinfo = $request['wxinfo'];
        $wx_id = ID();
        $user_id = ID();

        $nickname = app()->make(CommonService::class)->with('str',$wxinfo['nickname'])->run('userTextEncode');

        $data = [
            'id'         => $wx_id,
            'unionid'    => $wxinfo['unionid'],
            'openid'     => $wxinfo['openid'],
            'nickname'   => $nickname,
            'sex'        => $wxinfo['gender'],
            'city'       => $wxinfo['city'],
            'country'    => $wxinfo['country'],
            'headimgurl' => $wxinfo['avatarurl'],
            'user_id'    => $user_id
        ];

        $request['user_id'] = $user_id;
        $request['user_name'] = $nickname;
        $request['unionid'] = $wxinfo['unionid'];
        $request['headimgurl'] = $wxinfo['avatarurl'];
        $request['openid'] = $wxinfo['openid'];
        $this->repo->insert($data);
        $request = $this->checkRecommend($request);
        return $next($request);
    }

    //检查推荐用户
    public function checkRecommend($request){
        $request['register_type'] = '02';

        // --------------------------77-------------------------
        $login_name = config('const_user.RECOMMEND');

        if(!isset($request['recommendId']) || empty($request['recommendId'])){
            $login_name = config('const_user.RECOMMEND');
            $userInfo = $this->user->getUserByLoginName($login_name);
            $request['recommendId'] = $userInfo['user_id'];
            $request['register_type'] = '01';
        }

        if($request['recommendId']){
            $userInfo = $this->user->getUser($request['recommendId']);
            if(!$userInfo){
                $userInfo = $this->user->getUserByLoginName($login_name);
                $request['recommendId'] = $userInfo['user_id'];
            }
        }

        return $request;
    }

}