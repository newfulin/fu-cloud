<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 14:19
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InfoAuthenticationController extends Controller
{
    public function getRules()
    {
        return [
            'submitAuthInfo' => [
                'login_name'        => 'desc:手机号',
                'passwd'            => 'desc:登录密码',
                'userName'          => 'required',
                'crpIdType'         => 'required',  //证件类型
                'idNo'              => 'required|identitycards',  //身份证号
                'accountName'       => 'required|min:2|max:6|regex:/([\xe4-\xe9][\x80-\xbf]{2}){2,6}$/',  //持卡人姓名
                'accountNo'         => 'required|accountno',  //银行卡账号
                'bankLeaveMobile'   => 'required|mobile',   //银行预留手机号手机号
                'provinceName'      => 'required',    //开户省名称
                'cityName'          => 'required',    //开户市名称
                'openBankName'      => 'required',    //开户行名称,
                'bankCode'          => 'required',    //开户行简称
                'bankLineName'      => 'required',    //联行号名称
                'bankLineCode'      => 'required',    //联行号id
                'provinceId'        => 'required',    //省编码
                'cityId'            => 'required'     //市编码
            ]
        ];
    }

    /**
     * @desc 实名认证
     */
    public function submitAuthInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        Log::info('实名认证:|' .$user_id);

        return Access::service('InfoAuthenticationService')
            ->with('user_id', $user_id)
            ->with('userName', $request->input('userName'))
            ->with('login_name', $request->input('login_name'))
            ->with('pass_word', $request->input('passwd'))
            ->with('crpIdType', $request->input('crpIdType'))
            ->with('idNo', $request->input('idNo'))
            ->with('accountName', $request->input('accountName'))
            ->with('accountNo', $request->input('accountNo'))
            ->with('bankLeaveMobile', $request->input('bankLeaveMobile'))
            ->with('provinceName', $request->input('provinceName'))
            ->with('cityName', $request->input('cityName'))
            ->with('openBankName', $request->input('openBankName'))
            ->with('bankCode', $request->input('bankCode'))
            ->with('bankLineName', $request->input('bankLineName'))
            ->with('bankLineCode', $request->input('bankLineCode'))
            ->with('provinceId', $request->input('provinceId'))
            ->with('cityId', $request->input('cityId'))
            ->run('submitAuthInfo');
    }

    /**
     * @desc 判断用户是否实名
     */
    public function judgeAuthInfo(Request $request){
        $user_id = $request->user()->claims->getId();

        Log::info('判断用户是否实名:|' .$user_id);

        return Access::service('InfoAuthenticationService')
            ->with('user_id', $user_id)
            ->run('judgeAuthInfo');
    }

}