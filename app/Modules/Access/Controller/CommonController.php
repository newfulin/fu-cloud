<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/2
 * Time: 15:46
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommonController extends Controller
{
    public function getRules(){
        return [
            'getAreaBankList' => [
                'headEng'   => 'required',      //银行英文简称
                'stateName' => 'required',      //省名称
                'cityName'  => 'max:10',      //城市名称
//                'keyWord'   => 'min:1',      //关键词搜索
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'getCityList' => [
                'provinceId' => 'required'
            ],
            'getQrcode' => [
                'url' => 'desc:二维码跳转地址'
            ],
        ];
    }

    /**
     * @desc 根据城市,银行  获取银联列表
     * @param string headEng 银行英文简称
     * @param string stateName 省名称
     * @param string cityName 城市名称
     * @param string keyWord 关键词搜索
     */
    public function getAreaBankList(Request $request)
    {
        return Access::service('CommonService')
            ->with('headEng',$request->input('headEng'))
            ->with('stateName',$request->input('stateName'))
            ->with('cityName',$request->input('cityName'))
//            ->with('keyWord',$request['keyWord'])
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getAreaBankList');
    }

    /**
     * @desc 获取银行信息接口
     * comm support bank info
     */
    public function getSupportBankList()
    {
        return Access::service('CommonService')
            ->run('getSupportBankList');
    }

    /**
     * @desc 获取省
     */
    public function getProvinceList()
    {
        return Access::service('CommonService')
            ->run('getProvinceList');
    }

    /**
     * @desc 获取市
     */
    public function getCityList(Request $request)
    {
        return Access::service('CommonService')
            ->with('provinceId',$request->input('provinceId'))
            ->run('getCityList');
    }

    /**
     * @desc 获取联系我们 信息  电话
     */
    public function getContactInfo()
    {
        return Access::service('CommonService')
            ->run('getContactInfo');
    }

    /**
     * @desc 获取公众号配置信息
     */
    public function getWxConfigInfo(){
        return Access::service('CommonService')
            ->run('getWxConfigInfo');
    }

    /**
     * @desc 二维码生成
     */
    public function getQrcode(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info("查询用户流水:|" . $user_id);
        return Access::service('CommonService')
            ->with('url',$request->input('url'))
            ->with('user_id',$user_id)
            ->run('getQrcode');
    }

    /**
     * @desc 用户邀请码数量检测
     */
    public function checkInvitationCodeNumber(Request $request){
        return Access::service('CommonService')
            ->run('checkInvitationCodeNumber');
    }
}