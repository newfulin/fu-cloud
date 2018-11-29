<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:01
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class WxxCxLoginController extends Controller
{
    public function getRules()
    {
        return [
            'handle' => [
                'code' => 'required|desc:微信code',
                'iv' => 'required|desc:加密初始量',
                'encryptedData' => 'required|desc:密文串',
                'recommendId' => 'desc:推荐ID'
            ]
        ];
    }

    //SmallProgramLogin

    /**
     * @desc 小程序登陆
     */
    public function handle(Request $request){
        return Access::service('WxxCxLoginService')
            ->with('code',$request->input('code'))
            ->with('iv',$request->input('iv'))
            ->with('encryptedData',$request->input('encryptedData'))
            ->with('recommendId',$request->input('recommendId'))
            ->run('handle');
    }
}