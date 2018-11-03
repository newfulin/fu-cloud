<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 17:01
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommUserInfoController extends Controller
{
    public function getRules(){
        return [
            'updateUserName'=>[
                'oldUserName' =>'required',
                'newUserName' =>'required'
            ],
            'updatePassword' => [
                'loginName' => 'required',
                'password'  => 'required',
                'code'      => 'required'
            ],
            'forgetPassWord' => [
                'loginName' => 'required',
                'password'  => 'required',
                'code'      => 'required'
            ]
        ];
    }

    /**
     * @desc 获取用户信息
     */
    public function getUserInfo(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('获取用户信息 | ' .$user_id);
        return Access::service('CommUserInfoService')
            ->with('user_id',$user_id)
            ->run('getUserInfo');
    }

    /**
     * @desc 修改用户名
     */
    public function updateUserName(Request $request)
    {
        $oldUserName = $request->input('oldUserName');
        $newUserName = $request->input('newUserName');
        $user_id = $request->user()->claims->getId();
        Log::info($user_id . " 修改昵称:|" . $oldUserName . '--->' .$newUserName);

        return Access::service('CommUserInfoService')
            ->with('user_id',$user_id)
            ->with('username',$newUserName)
            ->run('updateUserNameById');
    }

    /**
    * @desc 修改密码
    */
    public function updatePassword(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("修改密码:|" . $user_id);

        return Access::service('CommUserInfoService')
            ->with('user_id',$user_id)
            ->with('loginName',$request->input('loginName'))
            ->with('password',$request->input('password'))
            ->with('code',$request->input('code'))
            ->run('updatePasswordById');
    }

    /**
     * @desc 忘记密码
     */
    public function forgetPassWord(Request $request)
    {
        $loginName = $request->input('loginName');

        Log::info("忘记密码:|" . $loginName);
        return Access::service('CommUserInfoService')
            ->with('loginName',$request->input('loginName'))
            ->with('password',$request->input('password'))
            ->with('code',$request->input('code'))
            ->run('forgetPassWordByLoginName');
    }

    /**
     * @desc 头像上传
     */
    public function uploadHeadImg(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info("头像修改:|" . $user_id);
        return Access::service('CommUserInfoService')
            ->with('user_id',$user_id)
            ->run('uploadHeadImg');
    }
}