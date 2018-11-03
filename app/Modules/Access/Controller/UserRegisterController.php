<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 14:59
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    public function getRules(){
        return [
            'userRegister' => [
                'userName'    => 'required',
                'mobile'      => 'required',//|regex:/^1[34578][0-9]{9}$/
                'recommendId' => '',
                'code'        => 'required|min:6|max:6'
            ]
        ];
    }

    /**
     * @desc 用户注册
     */
    public function userRegister(Request $request)
    {
        return Access::service('UserRegisterService')
            ->with('loginName',$request->input('userName'))
            ->with('mobile',$request->input('mobile'))
            ->with('recommendId',$request->input('recommendId'))
            ->with('code',$request->input('code'))
            ->runTransaction();
    }
}