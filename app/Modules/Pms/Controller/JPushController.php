<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29
 * Time: 14:34
 */

namespace App\Modules\Pms\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Pms\Pms;
use Illuminate\Http\Request;

class JPushController extends Controller
{
    public function getRules() {
        return [
            'singlePush' => [
//                'alias'     => 'required', // 标签值
                'message_type' => 'required', //消息类型
                //                'msgtype' => 'required|in:01,02',  //消息类型  01消息,02 公告
            ],
            'singlePushPms' => [
                'user_id' => 'required', // 标签值
                'title' => 'required', //消息标题
                'msg' => 'required', //消息内容
            ],
            'sendJPushMsg' => [
                'tag' => 'required', // 标签值
                'title' => 'required', //消息标题
                'msg' => 'required', //消息内容
                'msgtype' => 'required|in:01,02', //消息类型  01消息,02 公告
            ],
            'sendAllJPushMsg' => [
                'title' => 'required', //消息标题
                'msg' => 'required', //消息内容
                'msgtype' => 'required|in:01,02', //消息类型  01消息,02 公告
                'apptype' => 'required|in:all,ios,android', //客户端类型  所有在线用户
            ],
        ];
    }

    /**
     * @desc 用户单个推送 PMS
     */
    public function singlePushPms(Request $request) {
        return Pms::service('JPushService')
            ->with('alias', $request->input('user_id'))
            ->with('title', $request->input('title'))
            ->with('msg', $request->input('msg'))
            ->with('msgtype', '01')
            ->run('singlePushPms');

//        $request['target'] = $request['recommendId'];
        //        $request['message_type'] = 'USER_REGISTER_SUC';
    }

    /**
     * @desc 根据用户等级推送
     */
    public function sendJPushMsg(Request $request) {
        return Pms::service('JPushService')
            ->with('tag', $request->input('tag'))
            ->with('title', $request->input('title'))
            ->with('msg', $request->input('msg'))
            ->with('msgtype', $request->input('msgtype'))
            ->run('batchPushMsg');
    }

    /**
     * @desc 消息广播,所有用户
     */
    public function sendAllJPushMsg(Request $request) {
        return Pms::service('JPushService')
            ->with('title', $request->input('title'))
            ->with('msg', $request->input('msg'))
            ->with('msgtype', $request->input('msgtype'))
            ->with('apptype', $request->input('apptype'))
            ->run('sendAllJPushMsg');
    }
}