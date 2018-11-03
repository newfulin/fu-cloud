<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 9:13
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function getRules()
    {
        return [
            'doLogin' => [
                'loginName' => 'required|mobile',   //手机号
                'passWord'  => 'required|min:6'     //密码
            ],
            'cafeLogin' => [
                'loginName' =>'required|mobile',    //手机号
                'passWord' =>'required|min:6',      //密码
                'machine_code' =>'required',                //咖啡机编码
            ]
        ];
    }

    /**
     * @desc 手机用户登陆
     */
    public function doLogin(Request $request)
    {
        return Access::service('LoginService')
            ->with('loginName',$request->input('loginName'))
            ->with('passWord',$request->input('passWord'))
            ->run('doLoginProcess');
    }

    /**
     * @desc 咖啡机店主登陆
     * @param Request $request
     * @return mixed
     */
    public function cafeLogin(Request $request)
    {
        return Access::service('LoginService')
            ->with('loginName',$request->input('loginName'))
            ->with('machine_code',$request->input('machine_code'))
            ->with('passWord',$request->input('passWord'))
            ->run('cafeLogin');
    }
}