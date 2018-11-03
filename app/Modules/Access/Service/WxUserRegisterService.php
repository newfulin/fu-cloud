<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 17:45
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Events\UserRegistAfterEvent;
use App\Modules\Access\Middleware\CreateAccount;
use App\Modules\Access\Middleware\CreateTeamMiddle;
use App\Modules\Access\Middleware\CreateUserMiddle;
use App\Modules\Access\Middleware\CreateWxInfoMiddle;
use App\Modules\Access\Middleware\GetWxInfoMiddle;
use Illuminate\Support\Facades\Log;

class WxUserRegisterService extends Service
{
    public function getRules(){

    }

    public $middleware = [
        //创建微信信息
        CreateWxInfoMiddle::class,
        //创建用户
        CreateUserMiddle::class,
        //创建用户团队关系
        CreateTeamMiddle::class,
        //创建账户
        CreateAccount::class
    ];

    public $afterEvent = [
        UserRegistAfterEvent::class
    ];

    public function handle($request){
        Log::info('用户注册 -> ' .$request['unionid']);
        $request['target'] = $request['recommendId'];
        $request['message_type'] = 'USER_REGISTER_SUC';

        return $request;
    }
}