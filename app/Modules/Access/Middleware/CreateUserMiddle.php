<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/27
 * Time: 16:48
 */
namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class CreateUserMiddle extends Middleware{

    public $repo ;

    public function __construct(CommUserRepo $repo)
    {

        $this->repo = $repo;
    }
    
    public function handle($request, Closure $next)
    {
        Log::info('创建用户 ->' .$request['unionid']);
        $id = $request['user_id'];
        $data = [
            'id'               => $id,
            'user_id'          => $id,
            'user_name'        => $request['user_name'],
            'user_type'        => 10,
//            'login_name'       => $request['mobile'],
//            'login_name'       => '',
            'agent_id'         => config('const_user.FORMAL_AGENT.code'),
            'user_tariff_code' => 'P1101',
            'register_type'    => $request['register_type'],  //注册类型
            'level_name'       => config('const_user.ORDINARY_USER.code'),
            'status'           => config('const_user.SIGN_UP.code'),
            'last_login_time'  => date('Y-m-d H:i:s'),
            'create_time'      => date('Y-m-d H:i:s'),
            'create_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'headimgurl'       => $request['headimgurl'],
            'unionid'          => $request['unionid'],
//            'referral_code'    => $this->getReferralCode()
        ];

        $request['user_tariff_code'] = $data['user_tariff_code'];

        //添加数据库
        $this->repo->insert($data);

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