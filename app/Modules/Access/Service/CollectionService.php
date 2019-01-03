<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 18:34
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CollectCountRepo;
use App\Modules\Meet\Repository\ShareControlRepo;

class CollectionService extends Service
{
    public function getRules(){

    }

    //咖啡厅 会议 收藏
    public function collectionData(CollectCountRepo $repo, $request)
    {
        //添加 更新 操作
        $add = [
            'id' => ID(),
            'type' => $request['type'],
            'user_id' => $request['user_id'],
            'obj_id' => $request['obj_id'],
            'status' => '10',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => $request['user_id'],
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => $request['user_id'],
        ];

        $update = [
            'user_id' => $request['user_id'],
            'obj_id' => $request['obj_id']
        ];

        $ret = $repo->updateOrCreate($update,$add);

        if(!$ret){
            Err('收藏失败');
        }
        return "收藏成功";
    }

    //我的收藏
    public function myCollect(CollectCountRepo $repo, $request)
    {
        $ret = $repo->getMyCollertGoodsList($request);
        foreach($ret as $key => $val){
            if($val->img) $ret[$key]->img = R($val->img,false);
            if($val->img1) $ret[$key]->img1 = R($val->img1,false);
        }

        return $ret;
    }

    //取消收藏
    public function cancelCollect(CollectCountRepo $repo, $request){
        return $repo->cancelCollect($request);
    }

    //获取收藏数量
    public function getCollectCount(CollectCountRepo $repo,$request){
        return $repo->getCollectCount($request);
    }

    //判断收藏状态
    public function judgeCollect(CollectCountRepo $repo, $request)
    {
        $ret = $repo->getCollertInfo($request);
        if($ret){
            return 1;
        }
        return 0;
    }

    //我收藏的数量 会议 / 咖啡厅
    public function getMyCollectAllCount(CollectCountRepo $repo, $request){
        return $repo->getMyCollectAllCount($request);
    }
}