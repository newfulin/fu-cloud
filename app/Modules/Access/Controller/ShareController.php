<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 9:34
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function getRules(){
        return [
            'getWechatShareInfo' => [
                'page' => 'required',
                'pageSize' => 'required',
                'user_id' => '',
            ],
            'getShareInfo' => [
                'id' => 'required',
                'type' => 'required|desc:分享类型 10分享注册 20商品分享 30 头条 40 商城分享',
                'user_id' => '',
                'remark' => '',
            ],
            'getMeetShareInfo' => [
                'id' => 'required',
                'user_id' => '',
                'remark' => '',
            ],
            'webShare' => [
                'url' => 'required|desc:分享地址'
            ],
        ];
    }

    /**
     * @desc 获取注册分享列表
     * @param $request
     * @return mixed
     */
    public function getWechatShareInfo(Request $request)
    {
        return Access::service('ShareService')
            ->with('page', $request->input('page'))
            ->with('pageSize', $request->input('pageSize'))
            ->with('user_id', $request->input('user_id'))
            ->run('getWechatShareInfo');
    }

    /**
     * @desc 获取分享信息
     * @param $request
     * @return mixed
     */
    public function getShareInfo(Request $request)
    {
        $request->setTrustedProxies(array('172.16.50.22'));
        $ip = $request->getClientIp();
        return Access::service('ShareService')
            ->with('ip', $ip)
            ->with('id', $request->input('id'))
            ->with('user_id', $request->input('user_id'))
            ->with('remark', $request->input('remark'))
            ->with('kind_of', $request->input('type'))
            ->run('getShareInfo');
    }

    /**
     * @desc 二次分享数据
     */
    public function webShare(Request $request){
        return Access::service('ShareService')
            ->with('url', $request->input('url'))
            ->run('webShare');
    }
    /**
     * @desc 获取订阅信息
     */
    public function getSubscribe(Request $request){
        $userId = $request->user()->claims->getId();
        return Access::service('ShareService')
            ->with('userId',$userId)
            ->run('getSubscribe');
    }


}