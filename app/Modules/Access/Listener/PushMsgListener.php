<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/23
 * Time: 9:58
 */

namespace App\Modules\Access\Listener;


use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommPushRecordRepo;
use App\Modules\Access\Repository\CommUserRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Common\Util\JPush;
use Illuminate\Support\Facades\Log;

//
class PushMsgListener implements ShouldQueue
{
    public $msg;
    public function __construct(CommPushRecordRepo $msg)
    {
        $this->msg = $msg;
    }
    public function handle($event)
    {
        $request = $event->request;

        Log::info('异步消息推送');
//        $param  = config('const_sms.USER_REGISTER_SUC');
        $param  = config('const_sms.'.$request['message_type']);

        $message = Access::service('SmsService')
            ->with('data',$request)
            ->with('param',$param)
            ->run('pushMessage');

        $data =array(
            'id'              => ID(),
            'process_id_from' => 'app',
            'process_id_to'   => $request['target'],
            'business_code'   => $message['templet_id'],
            'title'           => $message['title'],
            'content'         => $message['message'],
            'type'            => 2,
            'status'          => 1,
            'msg_type'        => '02',
            'create_time'     => date('Y-m-d H:i:s'),
            'create_by'       => 'system',
            'update_time'     => date('Y-m-d H:i:s'),
            'update_by'       => 'system',
        );

        $ret = $this->msg->insert($data);

        if($ret){
            $arr = [
                'dataid' => $data['id'],
                'type' => $param['type'],
//                'path' => $param['path'],
            ];

            $client = new JPush();
            return $client->singlePush($data['process_id_to'],$data['title'],$data['content'],$arr);

        }else{
            Log::info('消息添加失败'.json_encode($data));
        }


//        singlePush($alias,$title,$msg,$data)



    }
}