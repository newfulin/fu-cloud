<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/1
 * Time: 9:20
 */
namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CommNotice;

class CommNoticeRepo extends Repository{
    public function __construct(CommNotice $model)
    {
        $this->model = $model;
    }

    public function getNoticeByType($type,$keyWord,$pageSize)
    {
        $ret = optional($this->model->select('id','notice_type','notice_title','notice_desc','target_url','update_time','type')
            ->where('notice_type', $type)
            ->where('close_status', 0)
            ->where('notice_title','like','%'.$keyWord.'%')
            ->orderBy('create_time','DESC')
            ->paginate($pageSize))
            ->toArray();
        return $ret;
    }
    public function getHelpList($noticeType,$type)
    {
        $ret = optional($this->model->select('id','notice_type','notice_title','notice_desc','target_url','update_time')
            ->where('notice_type', $noticeType)
            ->where('type',$type)
            ->where('close_status', 0)
            ->orderBy('create_time','DESC')
            ->get())
            ->toArray();
        return $ret;
    }

    //查询公告详情
    public function getNoticeInfoById($id)
    {
        return optional($this->model
            ->select('id','notice_type','notice_title','show_place','notice_content','image_id','target_url','update_time','create_time')
            ->where('id',$id)
            ->first())
            ->toArray();
    }
}