<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 15:04
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommFeedbackRepo;

class FeedbackMessageService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function setFeedbackInfo(CommFeedbackRepo $repo,$request)
    {
        $data = array(
            'id'          => ID(),
            'user_id'     => $request['user_id'],
            'basic_info'  => $request['basicInfo'],
            'content'     => $request['content'],
            'create_time' => date('Y-m-d H:i:s'),
            'create_by'   => $request['user_id'],
            'update_time' => date('Y-m-d H:i:s'),
            'update_by'   => 'admin',
            'status'      => 1
        );

        $repo->insert($data);
        return ;
    }
}