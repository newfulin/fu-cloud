<?php

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\ImgBannerRepo;
use Illuminate\Http\Request;

class HomeCon extends Controller
{
    public function getRules(){
        return [
            'getBanner' => [
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
     * @desc 获取展示图（首页）
     */
    public function getBanner(ImgBannerRepo $banner){
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