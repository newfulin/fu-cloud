<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7
 * Time: 17:46
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommPushRecordRepo;
use App\Modules\Access\Repository\CommUserRepo;

class UserMessageService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //查询消息列表
    public function getListByProccessId(CommUserRepo $user,CommPushRecordRepo $repo,$request)
    {
        $userInfo = $user->getUser($request['user_id']);
        $request['user_tariff_code'] = $userInfo['user_tariff_code'];
        $ret = $repo->getListByProccessId($request);
        foreach ($ret as $key => $val){
            $ret[$key]['img'] = config('const_sms.'.$val['msg_type'].'.img');
        }
        return $ret;
    }

    //查询消息内容
    public function getContentById(CommPushRecordRepo $repo,$request)
    {
        return $repo->getContentById($request);
    }
}