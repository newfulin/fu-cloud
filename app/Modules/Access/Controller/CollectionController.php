<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 18:30
 */

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function getRules(){
        return [
            'collectionData' => [
                'type' => 'required|desc:10 商品',
                'obj_id'   => 'required|desc:收藏数据ID',
            ],
            'myCollect' => [
                'type' => 'required|desc:10 商品',
                'page' => 'required',
                'pageSize' => 'required'
            ],
            'cancelCollect' => [
                'obj_id'   => 'required|desc:收藏数据ID'
            ],
            'getCollectCount' => [
                'obj_id'   => 'required|desc:收藏数据ID'
            ],
            'judgeCollect' => [
                'obj_id'   => 'required|desc:收藏数据ID'
            ]
        ];
    }

    /**
     * @desc 商品 收藏
     */
    public function collectionData(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info('收藏 | '.$user_id .'-----'.$request->input('obj_id'));
        return Access::service('CollectionService')
            ->with('type',$request->input('type'))
            ->with('obj_id',$request->input('obj_id'))
            ->with('user_id',$user_id)
            ->run('collectionData');
    }

    /**
     * @desc 我的收藏 10:咖啡厅  20:会议
     */
    public function myCollect(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        return Access::service('CollectionService')
            ->with('user_id',$user_id)
            ->with('type',$request->input('type'))
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('myCollect');
    }


    /**
     * @desc 取消收藏
     */
    public function cancelCollect(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        Log::info('取消收藏 | '.$user_id .'-----'.$request->input('id'));

        return Access::service('CollectionService')
            ->with('user_id',$user_id)
            ->with('obj_id',$request->input('obj_id'))
            ->run('cancelCollect');
    }

    /**
     * @desc 获取收藏数量
     */
    public function getCollectCount(Request $request){
        return Access::service('CollectionService')
            ->with('obj_id',$request->input('obj_id'))
            ->run('getCollectCount');
    }

    /**
     * @desc 判断是否收藏 该咖啡厅 会议
     */
    public function judgeCollect(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        return Access::service('CollectionService')
            ->with('user_id',$user_id)
            ->with('obj_id',$request->input('obj_id'))
            ->run('judgeCollect');
    }
}