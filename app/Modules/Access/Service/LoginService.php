<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 9:15
 */

namespace App\Modules\Access\Service;


//use App\Admin\Models\CoffeeMachine;
use App\Common\Contracts\Service;
//use App\Modules\Access\Repository\CafeMachineRepo;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Support\Facades\Log;

class LoginService extends Service
{
    public $repo;
    public function __construct(CommUserRepo $repo)
    {
        $this->repo = $repo;
//        $this->machine = $machine;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //用户登陆
    public function doLoginProcess(CommUserRepo $repo,$request)
    {
        Log::info("用户登陆-----".$request['loginName']);
        $ret = $this->processLogin($request['loginName'],md5($request['passWord']));

        //产生token
        $token = Token()
            ->setId($ret['id'])
            ->setName($ret['login_name'])
            ->setRole($ret['user_tariff_code'])
            ->getToken();
        return $token;
    }

    //用户登陆处理
    public function processLogin($username,$passwd)
    {
        $user = $this->repo->getUserByLoginName($username);
        //如果用户不存在
        if (!$user) {
            Err("USER_NO_EXIST");
        };

        if($user['status'] == config('const_user.LOGOUT.code')){
            Err('PULL_THE BLACK');
        }

        //密码错误判断
        if ($passwd != strtolower($user['pass_word'])) {
            Err('PASSWORD_ERROR');
        }
        $status = config('const_user.SIGN_UP.code');
        if ($status == $user['status']) {
            //注册用户首次登陆变更状态
            // APPROVE_USER
            $code = config('const_user.APPROVE_USER.code');
            $this->repo->updateUser($user['user_id'], array('status'=>$code));
        }
        $this->repo->updateUser($user['user_id'], array('last_login_time'=>date('Y-m-d H:i:s')));
        return $user;

    }
    //咖啡机登陆
    public function cafeLogin($request)
    {
        Log::info('----------店主登陆------------'.$request['loginName']);

        $user = $this->repo->getUserByLoginName($request['loginName']);
        if(!$user){
            Err("USER_NO_EXIST");
        }
        if(md5($request['passWord']) != strtolower($user['pass_word'])){
            Err("PASSWORD_ERROR");
        }
        $ids = $this->machine->getUserIds();
        foreach($ids as $k =>  $v){
        if($v['user_id'] == $user['user_id']){
            $this->machine->updatePort($v['user_id'], array('machine_code' => $request['machine_code'],'login_name' => $request['loginName']));
            $token = Token()
                ->setId($user['id'])
                ->setName($user['login_name'])
                ->setRole($request['machine_code'])
                ->getToken();
            return $token;
            }
        }
        Err('该用户不是店主!');
    }
}