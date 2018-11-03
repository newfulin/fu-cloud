<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 17:39
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Common\Util\Tool;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Support\Facades\Log;
use Closure;

class CheckMobileMiddle extends Middleware
{
    
    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        //验证手机号是否注册
        $this->checkMobile($request);

        //验证  验证码是否正确
        $tool = new Tool();
        $tool->checkCaptcha($request['mobile'],$request['code']);
        $request['register_type'] = '02';
        //检查推荐用户是否存在
            Log::info('推荐用户'.$request['recommendId']);
        if($request['recommendId'] == 'null' || !$request['recommendId']){
            $login_name = config('const_user.RECOMMEND');
            $userInfo = $this->repo->getUserByLoginName($login_name);
            $request['recommendId'] = $userInfo['user_id'];
            $request['register_type'] = '01';
        }else{
            $this->repo->getUser($request['recommendId']);
        }

        return $next($request);
    }

    /**
     * 验证手机号是否注册
     */
    public function checkMobile($request)
    {
        $ret = $this->repo->getUserByLoginName($request['mobile']);

        if($ret) Err('USER_MOBILE_EXIT');
    }
}