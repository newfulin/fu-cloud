<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 13:50
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class CommNoticeController extends Controller
{
    public function getRules(){
        return [
            'getNoticeList' => [
                'type'     => 'required|desc:公告类型 10(公告类型)20(文章类型)  30(帮助) 40(新闻) 50(使用教程)',
                'key_word' => '',
                'page'     => 'required',
                'pageSize' => 'required',
            ],
            'getNoticeInfo' => [
                'id' => 'required|desc:公告id',
            ]
        ];
    }

    /**
     * @desc 获取公告列表  1(公告类型) 2(文章类型)  3(帮助) 4(新闻) 5(使用教程)
     * @return string type 01 独家 02 精选
     */
    public function getNoticeList(Request $request)
    {
        return Access::service('WidgetService')
            ->with('type',$request->input('type'))
            ->with('key_word',$request->input('key_word'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getNoticeList');
    }

    /**
     * @desc 公告详情
     */
    public function getNoticeInfo(Request $request)
    {
        return Access::service('WidgetService')
            ->with('id',$request->input('id'))
            ->run('getNoticeInfo');
    }
}