<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 16:02
 */

namespace App\Modules\Finance\Bean\ProcessBean;

use Illuminate\Support\Facades\Log;


/**
 * 红包使用 获取用户编号 同时作为是否发放红包用的.
 */
class UserNumberHb {

    public function handle($request)
    {
        Log::debug("getProcessId.UserNumber.handle...");
        $template = $request['book']['template'];
        $userinfo = $request['userinfo'];
        $userId = $userinfo['user_id'];
        return $userId;
    }
}