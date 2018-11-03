<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 17:43
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserMessageController extends Controller
{
    public function getRules()
    {
        return [
            'getMessageList' => [
                'page' =>'required',
                'pageSize' =>'required',
                'type' =>'',
            ],
            'getMsgContent' => [
                'id' => 'required'
            ]
        ];
    }

    /**
     * @desc 查询消息列表
     */
    public function getMessageList(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        Log::info('信息列表:|' .$user_id);
        return Access::service('UserMessageService')
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('type',$request->input('type'))
            ->run('getListByProccessId');
    }

    /**
     * @desc 查询消息内容
     */
    public function getMsgContent(Request $request)
    {
        return Access::service('UserMessageService')
            ->with('id',$request->input('id'))
            ->run('getContentById');
    }
}