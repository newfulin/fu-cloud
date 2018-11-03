<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 17:50
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class HeadLineController extends Controller
{
    public function getRules(){
        return [
            'getHeadLineList' => [
                'type'     => 'desc:头条类型 10 日常 20 心情 30 吐槽 40 关注',
                'page'     => 'required',
                'pageSize' => 'required',
            ],
            'getHeadLineInfo' => [
                'id' => 'required'
            ],
            'setIncLike' => [
                'id' => 'required'
            ]
        ];
    }

    /**
     * @desc 获取头条菜单列表
     */
    public function getHeadLineMenu(){
        return config('const_headline');
    }

    /**
     * @desc 获取头条列表 默认获取最新
     */
    public function getHeadLineList(Request $request){
        return Access::service('HeadLineService')
            ->with('type',$request->input('type'))
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getHeadLineList');
    }

    /**
     * @desc 获取头条详情
     */
    public function getHeadLineInfo(Request $request){
        return Access::service('HeadLineService')
            ->with('id',$request->input('id'))
            ->run('getHeadLineInfo');
    }

    /**
     * @desc 文章点赞
     */
    public function setIncLike(Request $request){
        return Access::service('HeadLineService')
            ->with('id',$request->input('id'))
            ->run('setIncLike');
    }
}