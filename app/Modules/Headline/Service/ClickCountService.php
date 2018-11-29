<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 15:37
 */

namespace App\Modules\Headline\Service;


use App\Common\Contracts\Service;
use App\Modules\Headline\Repository\ClickCountRepo;

class ClickCountService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //通过类型点赞
    public function dataClick(ClickCountRepo $repo, $request)
    {
//        dd(222);
        //添加更新操作
        $add = [
            'id' => ID(),
            'type' => $request['type'],
            'obj_id' => $request['obj_id'],
            'user_id' => $request['user_id'],
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => $request['user_id'],
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => $request['user_id']
        ];

        $update = [
            'user_id' => $request['user_id'],
            'obj_id' => $request['obj_id']
        ];

        $ret = $repo->updateOrCreate($update,$add);

        if(!$ret) Err('点赞错误');

        return;

    }

    //判断点赞状态
    public function judgeDataClick(ClickCountRepo $repo, $request)
    {
        $ret = $repo->judgeDataClick($request);

        if($ret){
            return 1;
        }
        return 0;
    }

    //取消点赞
    public function cancelDataClick(ClickCountRepo $repo, $request)
    {
        return  $repo->cancelDataClick($request);

    }

    //获取点赞数量
    public function getClickCount(ClickCountRepo $repo, $request)
    {
        return $repo->getClickCount($request);
    }
}