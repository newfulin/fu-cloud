<?php
namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\ShareControl;
use Illuminate\Support\Facades\Log;


class ShareControlRepository extends Repository
{
    public function __construct(ShareControl $model)
    {
        $this->model = $model;
    }

    /**
     * 更新数据
     */
    public function update($data,$Id)
    {
        $this->model->where('id','=',$Id)->update($data);
    }
    // 获取统计数量
    public function getCount($shareId)
    {
        $re = $this->model
            ->where('share_id',$shareId)
            ->count();
        return $re;
    }
    /**
     * 插入保存
     */
    public function save($data)
    {
        $this->model->insert($data);
    }
    public function createShareCount($data)
    {
        return $this->model->insert($data);
    }

    /**
     *  同用户 同类型 每天 只统计一次分享
     */
    public function checkNum($user_id,$id,$time)
    {
        $data =date('Y-m-d',$time);
        $re = optional($this->model
            ->select('create_time')
            ->where('user_id',$user_id)
            ->where('share_id',$id)
            ->where('open_id',null)
            ->orderby('create_time', 'desc')
            ->first())
            ->toArray();
        if (!$re) {
            Log::info('首次分享----------------'.$user_id);
            return 'false';
        }
        $check = substr($re['create_time'],0,'10');
        if ($data != $check) {
            return 'false';
        }
        return $check.'|'.$data;
    }
    public function checkOpenId($user_id,$id,$time,$open_id)
    {
        $data =date('Y-m-d',$time);
        $re = optional($this->model
            ->select('open_id','create_time')
            ->where('user_id',$user_id)
            ->where('share_id',$id)
            ->where('open_id',$open_id)
            ->orderby('create_time', 'desc')
            ->first())
            ->toArray();
        if (!$re) {
            Log::info('-------userId---------'.$user_id.'-------id---------'.$id.'-------time---------'.$time.'-------open_id---------'.$open_id);
            return 'false';
        }
        $check = substr($re['create_time'],0,'10');
        if ($data != $check) {
            return 'false';
        }
        return $check.'|'.$data.'|'.$re['open_id'];
    }
    /**
     * @desc 获取我的分享
     * @param $user_id
     * @return mixed
     */
    public function getMyShare($data)
    {
        //            ->whereIn('type',$arr)

        $arr = array(
            '01'
        );
        $sql = $this->model
            ->select('id','user_id','open_id','type')
            ->where('user_id',$data['user_id'])
            ->where('create_time','>',$data['startTime'])
            ->where('create_time','<',$data['endTime']);
        if($data['type']) {
            $sql->where('type',$data['type']);
        }
        if($data['open_id']) {
            $sql->where('open_id','!=','');
        } else {
            $sql->where('open_id','');
        }

        $re = optional(
            $sql->get())
            ->toArray();

        return count($re);
    }

    public function getList($data)
    {
        $sql = $this->model
            ->select('user_id')
            ->where('user_id','!=','')
            ->where('create_time','>',$data['startTime'])
            ->where('create_time','<',$data['endTime']);
        //if($data['type']) {
        //    $sql->where('type',$data['type']);
        //}
        if($data['open_id']) {
            $sql->where('open_id','!=','');
        } else {
            $sql->where('open_id','');
        }

        $re = optional(
            $sql->get())
            ->toArray();
        return $re;
    }

}