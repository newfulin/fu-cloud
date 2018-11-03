<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:22
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\UpdateBriefRepo;

class UpdateBriefService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    // 获取功能介绍列表
    public function getIntroduceList(UpdateBriefRepo $repo,$request)
    {
        $re = $repo->getIntroduceList($request['pageSize']);
        return $re;
    }
    //获取功能介绍详情
    public function getIntroduceInfo(UpdateBriefRepo $repo,$request)
    {
        $re = $repo->getIntroduceInfo($request['id']);
        return $re;
    }
}