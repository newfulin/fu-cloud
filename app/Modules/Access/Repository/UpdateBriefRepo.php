<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:23
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\UpdateBrief;

class UpdateBriefRepo extends Repository
{
    public function __construct(UpdateBrief $model)
    {
        $this->model = $model;
    }

    // 获取功能介绍列表
    public function getIntroduceList($pageSize)
    {
        $ret = optional($this->model
            ->select('id','update_title','update_date')
            ->orderBy('create_time','desc')
            ->paginate($pageSize))
            ->toArray();
        return $ret['data'];
    }

    // 获取功能介绍详情
    public function getIntroduceInfo($id)
    {
        $ret = optional($this->model
            ->select('id','update_title','update_date','update_content')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $ret;
    }
}