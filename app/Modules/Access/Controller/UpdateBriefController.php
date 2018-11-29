<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:21
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class UpdateBriefController extends Controller
{
    public function getRules()
    {
        return [
            'getIntroduceList' => [
                'page' => 'required',
                'pageSize' => 'required',
            ],
            'getIntroduceInfo' => [
                'id' => 'required',
            ],
        ];
    }


    /**
     * @desc 获取功能介绍列表
     * @param Request $request
     * @return mixed
     */
    public function getIntroduceList(Request $request)
    {
        return Access::service('UpdateBriefService')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getIntroduceList');

    }

    /**
     * @desc 获取功能介绍详情
     * @param Request $request
     * @return mixed
     */
    public function getIntroduceInfo(Request $request)
    {
        $re = Access::service('UpdateBriefService')
            ->with('id',$request->input('id'))
            ->run('getIntroduceInfo');
        $re['update_content'] = makeJsContent($re['update_content']);
        return $re;
    }
}