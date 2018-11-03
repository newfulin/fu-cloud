<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 14:42
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommPushRecord;
use Illuminate\Database\Eloquent\Model;

class CommPushRecordRepo extends Repository
{
    public function __construct(CommPushRecord $model)
    {
        $this->model = $model;
    }

    /**
     * @param $user_id,$pageNum,$pageSize,$tariff_code
     * @param user_id string 用户ID
     * @param $pageNum number 页码
     * @param $pageSize number 条数
     * @param $tariff_code 用户等级
    */
    public function getListByProccessId($request)
    {
        $ret = optional($this->model
            ->select('id','process_id_from','process_id_to','title','url','status','create_time','content','msg_type')
            ->where('status',1)
            ->whereIn('process_id_to',[$request['user_id'],'all',$request['user_tariff_code'],$request['type']])
            ->orderBy('create_time','desc')
            ->paginate($request['pageSize'])
            )->toArray();
        return $ret['data'];
    }

    /**
     * @查询消息内容
     * @param id string 消息id
    */
    public function getContentById($request)
    {
        $ret = optional($this->model
            ->select('title','content','status','create_time')
            ->where('id',$request['id'])
            ->first())
            ->toArray();
        return $ret;
    }
}