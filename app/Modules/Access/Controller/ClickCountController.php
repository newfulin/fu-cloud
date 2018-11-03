<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 13:57
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClickCountController extends Controller
{
    public function getRules(){
        return [
            'dataClick' => [
                'type' => 'required|desc:10 咖啡厅 20 会议 30文章',
                'obj_id'   => 'required|desc:点赞数据ID',
            ],
            'getClickCount' => [
                'obj_id'   => 'required|desc:数据ID',
            ],
            'judgeDataClick' => [
                'obj_id' => 'required|desc:数据ID'
            ],
            'cancelDataClick' => [
                'obj_id' => 'required|desc:数据ID'
            ]
        ];
    }

    /**
     * @desc 点赞
     */

    public function dataClick(Request $request){

        $user_id = $request->user()->claims->getId();
        Log::info('点赞 | '.$user_id .'-----'.$request->input('obj_id'));
        return Access::service('ClickCountService')
            ->with('type',$request->input('type'))
            ->with('obj_id',$request->input('obj_id'))
            ->with('user_id',$user_id)
            ->run('dataClick');
    }

    /**
     * @desc 判断点赞状态
     */
    public function judgeDataClick(Request $request){
        $user_id = $request->user()->claims->getId();
        return Access::service('ClickCountService')
            ->with('obj_id',$request->input('obj_id'))
            ->with('user_id',$user_id)
            ->run('judgeDataClick');
    }

    /**
     * @desc 取消点赞
     */
    public function cancelDataClick(Request $request){
        $user_id = $request->user()->claims->getId();

        Log::info('取消收藏 | '.$user_id .'-----'.$request->input('id'));

        return Access::service('ClickCountService')
            ->with('user_id',$user_id)
            ->with('obj_id',$request->input('obj_id'))
            ->run('cancelDataClick');
    }

    /**
     * @desc 获取点赞数量
     */
    public function getClickCount(Request $request){
        return Access::service('ClickCountService')
            ->with('obj_id',$request->input('obj_id'))
            ->run('getClickCount');
    }
}