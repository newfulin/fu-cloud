<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 17:16
 */

namespace App\Modules\Headline\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Headline\Headline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopController extends Controller
{
    public function getRules()
    {
        return [
            'getTopList' => [
                'top_type' => 'desc:头条类型 10 日常 20心情 30吐槽 40 关注',
                'page' => 'required',
                'pageSize' => 'required',
            ],
            'getTopInfo' => [
                'id' => 'required'
            ],
            'setForward' => [
                'id' => 'required'
            ],
        ];
    }

    /**
     * @desc获取头条列表
     * @param Request $request
     * @return mixed
     */
    public function getTopList(Request $request)
    {
        return Headline::service('TopService')
            ->with('page', $request->input('page'))
            ->with('pageSize', $request->input('pageSize'))
            ->with('top_type', $request->input('top_type'))
            ->run('getTopList');
    }

    /**
     * @desc 获取头条详情
     */
    public function getTopInfo(Request $request)
    {
        return Headline::service('TopService')
            ->with('id', $request->input('id'))
            ->run('getTopInfo');
    }

}
