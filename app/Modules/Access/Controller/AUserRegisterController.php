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

class AUserRegisterController extends Controller
{
    public function getRules(){
        return [
            'userRegister' => [
                'userName'    => 'required',
                'mobile'      => 'required|regex:/^1[34578][0-9]{9}$/',
                'level'       => 'required',
                'recommendId' => ''
            ]
        ];
    }

    /**
     * @desc 市场总监 创建
     */
    public function userRegister(Request $request)
    {
        return Access::service('AUserRegisterService')
            ->with('loginName',$request->input('userName'))
            ->with('mobile',$request->input('mobile'))
            ->with('recommendId',$request->input('recommendId'))
            ->with('level',$request->input('level'))
            ->run();
    }
}