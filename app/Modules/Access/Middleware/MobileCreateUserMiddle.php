<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21
 * Time: 9:09
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class MobileCreateUserMiddle extends Middleware
{
    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request,Closure $next){
        Log::info('创建用户 ->' .$request['mobile']);
        $id = ID();
        $data = [
            'id'               => $id,
            'user_id'          => $id,
            'status'           => 10,
            'user_name'        => $request['loginName'],
            'user_type'        => 10,
            'login_name'       => $request['mobile'],
            'agent_id'         => config('const_user.FORMAL_AGENT.code'),
            'user_tariff_code' => 'P1101',
            'register_type'    => $request['register_type'],
            'level_name'       => config('const_user.ORDINARY_USER.code'),
            'status'           => config('const_user.SIGN_UP.code'),
            'pass_word'        => md5($request['code']),
            'last_login_time'  => date('Y-m-d H:i:s'),
            'create_time'      => date('Y-m-d H:i:s'),
            'create_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
//            'referral_code'    => $this->getReferralCode()
        ];
//
//        //添加数据库
        $this->repo->insert($data);

        $request['user_id'] = $id;
        $request['id'] = $id;
        $request['user_name'] = $data['user_name'];
        return $next($request);
    }

    protected function getReferralCode()
    {
        $id = substr(ID(),11,8);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";
        for ($i = 0; $i < 2; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        $re = $str.$id;
        return $re;
    }
}