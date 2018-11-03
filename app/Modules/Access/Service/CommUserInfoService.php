<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 17:03
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Common\Util\Tool;
use App\Modules\Access\Access;
use App\Modules\Access\Middleware\UploadFileMiddle;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Support\Facades\Log;

class CommUserInfoService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        UploadFileMiddle::class => [
            'only' => 'uploadHeadImg'
        ]
    ];

    //获取用户信息
    public function getUserInfo(CommUserRepo $repo,$request){
        $ret = $repo->getUser($request['user_id']);
        if(empty($ret)){
            Log::info('用户信息获取失败: '.$request['user_id']);
            Err('用户信息获取失败:1010');
        }

        $code = config('const_user.OFFICIALLY.code');

        $ret['flag'] = 'false';
        $ret['level_name'] = config('const_user.'.$ret['level_name'].'.msg');

        if($ret['status'] == $code){
            $ret['credit_img'] = config('const_bank.'. strtoupper($ret['bank_code']).'.code');

            $suffix = substr($ret['account_no'],-4,4);

            $ret['after'] = $suffix;
            $ret['flag'] = 'true';
        }

        $ret['mobile'] = $ret['login_name'];
        $ret['login_name'] = Mobile($ret['login_name']);
        $ret['level_img'] = config('const_user.'.$ret['user_tariff_code'].'.code');
        return $ret;
    }

    //修改用户名
    public function updateUserNameById(CommUserRepo $repo,$request)
    {
        $username = app()->make(CommonService::class)->with('str',$request['username'])->run('userTextEncode');

        $data = [
            'user_name' => $username
        ];

        $ret = $repo->update($request['user_id'],$data);

        if($ret == 1 || $ret == 0) return $ret;
        else Err('修改失败');
    }

    //修改密码
    public function updatePasswordById(CommUserRepo $repo,$request)
    {
        //验证 手机验证码  code   修改密码
        $tool = new Tool();
        $tool->checkCaptcha($request['loginName'],$request['code']);

        $data = array('pass_word'=>md5($request['password']));
        $ret = $repo->updateUser($request['user_id'],$data);
        if($ret == 1 || $ret == 0) return $ret;
        else Err('修改失败');
    }

    //忘记密码
    public function forgetPassWordByLoginName(CommUserRepo $repo,$request)
    {
        $userInfo = $repo->getUserByLoginName($request['loginName']);
        if(!$userInfo){
            Err('该账号不存在');
        }

        //验证 手机验证码  code   修改密码
        $tool = new Tool();
        $tool->checkCaptcha($request['loginName'],$request['code']);

        $data = array('pass_word'=>md5($request['password']));
        $ret = $repo->updateUserPass($request['loginName'],$data);

        if($ret == 1 || $ret == 0) return $ret;
        else Err('修改失败');
    }

    //头像上传
    public function uploadHeadImg(CommUserRepo $repo,$request){
        $data = [
            'headimgurl' => strstr($request['path'],'upload/')
        ];
        $repo->updateUser($request['user_id'],$data);
        return R($data['headimgurl']);
    }
}