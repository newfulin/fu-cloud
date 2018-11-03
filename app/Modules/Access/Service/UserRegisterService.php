<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 15:01
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Events\PushMsgAfterEvent;
use App\Modules\Access\Events\UserRegistAfterEvent;
use App\Modules\Access\Middleware\CheckMobileMiddle;
use App\Modules\Access\Middleware\CreateAccount;
use App\Modules\Access\Middleware\CreateTeamMiddle;
use App\Modules\Access\Middleware\CreateUserMiddle;
use App\Modules\Access\Middleware\MobileCreateUserMiddle;
use Illuminate\Support\Facades\Log;

class UserRegisterService extends Service
{
    public function getRules(){

    }

    public $middleware = [
        //检查手机号  验证码
        CheckMobileMiddle::class,
        //创建用户
        MobileCreateUserMiddle::class,
//        CreateUserMiddle::class,
        //创建用户团队关系
        CreateTeamMiddle::class,
        //创建账户
        CreateAccount::class
    ];

    public $afterEvent = [
        UserRegistAfterEvent::class,
        PushMsgAfterEvent::class
    ];

    public function handle($request)
    {
        Log::info('用户注册 -> ' .$request['mobile']);
        $request['target'] = $request['recommendId'];
        $request['message_type'] = 'USER_REGISTER_SUC';
        $request['headimgurl'] = '';
        $request['unionid'] = '';

        return $request;
    }

}