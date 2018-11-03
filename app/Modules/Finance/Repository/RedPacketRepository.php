<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 15:49
 */

namespace App\Modules\Finance\Repository;


use App\Common\Models\RedPacket;
use Illuminate\Support\Facades\DB;
use App\Common\Contracts\Repository;

class RedPacketRepository extends Repository
{
    public function __construct(RedPacket $model)
    {
        $this->model = $model;
    }

    //根据用户ID查询红包
    public function getRedPacketListByUserId($request)
    {
        $ret = optional($this->model
            ->select('id','packet_name','packet_amount','granting_object','desr','granting_time','status')
            ->where('granting_object',$request['user_id'])
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }
    
    /**
     * 获取红包类型
     */
    public function getPacketManage($name='VIP红包')
    {
        $ret = DB::select("SELECT `id`, `packet_name`, `packet_amount`, `granting_type`, `greeting`, `limit_amount`, `desr`, `granting_amount`, `status` FROM packet_manage WHERE packet_name  = '$name'");
        return json_decode(json_encode($ret[0]),true);
    }
    /**
     * 获取指定用户的红包数量
     */
    public function getHBCountByUserId($userId,$packetAmount){
        $ret = $this->model->where('granting_object','=',$userId)->where('packet_amount','=',$packetAmount)->count();
        return $ret;
    }

    /**
     * 获取定用户的红包列表信息
     */
    public function getMyPacket($where){
        $ret = $this->model->where('granting_object','=',$where['granting_object'])
        ->where('status','=',$where['status'])->where('packet_amount','=',$where['packet_amount'])->orderby('id')->get();
        return $ret;
    }

    /**
     * 插入红包流水
     */
    public function save($data)
    {
        $this->model->insert($data);
    }
}