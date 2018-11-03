<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 16:03
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\TeamRelation;
use Illuminate\Support\Facades\DB;

class TeamRelationRepo extends Repository
{
    public function __construct(TeamRelation $model)
    {
        $this->model = $model;
    }

    //检测 当前用户关系(直推间推顶推)
    public function checkMyRecommend($user_id,$old_user_id)
    {
        $ret = optional($this->model
            ->select('user_id')
            ->where('user_id',$user_id)
            ->where('team_user_level','like','%'.$old_user_id.'%')
            ->first())
            ->toArray();
        return $ret;
    }

    // 检测用户关系(直推)
    public function checkParent1($userId,$parent1)
    {
        $ret = optional($this->model
            ->select('id')
            ->where('user_id',$userId)
            ->where('parent1',$parent1)
            ->first())
            ->toArray();
        return $ret;
    }

    //查询团队关系
    public function getRelation($user_id)
    {
        $ret = optional($this->model
            ->select('id','user_id','parent1','parent2','parent3','parent4','parent5',
                'parent6','parent7','parent8','parent9','parent10')
            ->where('user_id',$user_id)
            ->where('status',1)
            ->first())
            ->toArray();
        return $ret;
    }

    //更新推荐关系
    public function updateRecommendRela($user_id,$recommendId)
    {
        return DB::select('UPDATE team_relation as t1, (select team_user_level from team_relation where user_id = \''.$recommendId.'\' ) as t2   
                SET t1.team_user_level = CONCAT(\''.$recommendId. ',' . '\',ifnull(t2.team_user_level,""))
                WHERE t1.user_id = \''.$user_id.'\'');
    }

    //数据添加
    public function insertData($data)
    {
        return $this->model->insert($data);
    }

    //查询无推荐关系用户
    public function getParent1Null()
    {
        $ret = optional($this->model
            ->select('user_id', 'user_name')
            ->where('parent1', null)
            ->get())
            ->toArray();
        return $ret;
    }

    //查询直推用户信息
    public function getParent1Info($user_id){
        return optional(DB::table('team_relation as t0')
            ->select('t1.user_id','t1.user_tariff_code')
            ->leftJoin('comm_user_info as t1',function($join){
                $join->on('t0.user_id', '=', 't1.user_id');
            })
            ->where('t0.parent1',$user_id))
            ->get()
            ->toArray();
    }
}