<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 13:58
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommNoticeRepo;

class CommNoticeService extends Service
{
    public function getRules(){

    }

    //获取公告列表
    public function getNoticeList(CommNoticeRepo $repo,$request){
        $ret = $repo->getNoticeByType($request['type'],$request['key_word'],$request['pageSize']);
        return $ret['data'];
    }

    //公告详情
    public function getNoticeInfo(CommNoticeRepo $repo,$request){
        return $this->repo->getNoticeInfoById($request['id']);
    }
}