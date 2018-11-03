<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 13:59
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class WxLoginController extends Controller
{
    public function getRules()
    {
        return [
            'wxDoLogin' => [
                'code' => 'required|desc:微信code',
                'recommend_id' => 'desc:推荐ID',
                'flag' => 'default:true|desc:true 开放平台 false 公众号 program 小程序',
            ]
        ];
    }

    /**
     * @desc 微信登陆
     */
    public function wxDoLogin(Request $request){
        return Access::service('WxLoginService')
            ->with('code',$request->input('code'))
            ->with('recommendId',$request->input('recommend_id'))
            ->with('flag',$request->input('flag'))
            ->run('doLoginProcess');
    }
}