<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29
 * Time: 14:35
 */

namespace App\Modules\Pms\Service;


use App\Common\Contracts\Service;
use App\Common\Util\JPush;
use App\Modules\Access\Events\PushMsgAfterEvent;
use App\Modules\Access\Repository\CommPushRecordRepo;
use Illuminate\Support\Facades\Log;

class JPushService extends Service
{
    public $msg;
    public $notice;

    public $afterEvent = [
        PushMsgAfterEvent::class => [
            'only' => 'singlePush',
        ],
    ];

    public function __construct(CommPushRecordRepo $msg) {
        $this->msg = $msg;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * 批量推送消息
     * @param tag string 标签 (P1102,P1202...)
     * @param title string 消息标题
     * @param msg string 消息内容
     * @param type string 消息类型 01 02 03 04 (消息,公告,任务,分润账单)
     */
    public function batchPushMsg($request) {
        $data = $this->insertInfo($request['title'], $request['msg'], $request['msgtype'], $request['tag']);
        $data['type'] = $request['msgtype'];
        $client = new JPush();
        $ret = $client->batchPush($request['tag'], $request['title'], $request['msg'], $data);

        if ($ret['http_code'] != 200) {
            Err('消息推送失败');
        }

        Log::info('批量消息推送成功 | ' . $data['dataid'] . 'tag----' . $request['tag']);

        return $ret;
    }

    /**
     * 消息广播,所有用户
     */
    public function sendAllJPushMsg($request) {
        $data = $this->insertInfo($request['title'], $request['msg'], $request['msgtype'], 'all');

        $client = new JPush();
        $ret = $client->allJPushMsg($request['title'], $request['msg'], $request['apptype'], $data);

        if ($ret['http_code'] != 200) {
            Err('消息推送失败');
        }

        Log::info('消息推送成功 | ' . $data['dataid']);

        return $ret;
    }

    /**
     *　用户推送 个推
     */
    public function singlePush($request) {
        return $request;
//        $data = $this->insertInfo($request['title'],$request['msg'],'01',$request['alias']);
        //        $data = ['dataid' => ID()];
        //        $client = new JPush();
        //        return $client->singlePush($request['alias'],$request['title'],$request['msg'],$data);
    }

    public function singlePushPms($request) {
        $data = $this->insertInfo($request['title'], $request['msg'], $request['msgtype'], $request['alias']);

        $client = new JPush();
        $ret = $client->singlePush($request['alias'], $request['title'], $request['msg'], $data);

        if ($ret['http_code'] != 200) {
            Err('消息推送失败');
        }

        Log::info('消息推送成功 | ' . $data['dataid']);

        return $ret;
    }

    //消息 插入
    public function insertInfo($title, $msg, $msgtype, $tag = '') {
        $msgtype = config('const_sms.' . $msgtype . '.code');

        $actionName = 'get' . $msgtype;

        $data = $this->$actionName($title, $msg, $tag);
        $data['from'] = $msgtype;
        return $data;
    }

    //消息
    public function getmessage($title, $msg, $tag = 'all') {
        $data = array(
            'id' => ID(),
            'process_id_from' => 'app',
            'process_id_to' => $tag,
            'business_code' => '',
            'title' => $title,
            'content' => $msg,
            'type' => 2,
            'status' => 1,
            'msg_type' => '01',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => 'system',
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => 'system',
        );
        $ret = $this->msg->insert($data);

        if ($ret) {
            return $arr = ['dataid' => $data['id']];
        }

        Err('消息添加失败');
    }

    //公告
    public function getnotice($title, $msg, $tag = 'all') {
        $data = array(
            'id' => ID(),
            'notice_type' => 1,
            'notice_title' => $title,
            'notice_content' => $msg,
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => 'system',
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => 'system',
        );
        $ret = $this->msg->insert($data);

        if ($ret) {
            return $arr = ['dataid' => $data['id']];
        }

        Err('消息添加失败');
    }
}