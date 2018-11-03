<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/21
 * Time: 17:29
 */

namespace Nxp\Team\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\TeamRelation;
use Illuminate\Support\Facades\DB;

class NxpTeamRetionRepo extends Repository
{
    public function __construct(TeamRelation $model)
    {
        $this->model = $model;
    }

    //查询推广
    public function getMyPromotion($request)
    {
        if (!$request['pageSize']) {
            $request['pageSize'] = 10;
        }
        $ret = optional(DB::table('team_relation as t1')
            ->select('t1.id', 't1.user_id' ,'t1.create_time' ,'t2.headimgurl', 't2.login_name', 't2.user_tariff_code', 't2.user_name')
            ->where('t1.parent1', $request['user_id'])
            ->where('t1.user_id', '!=', $request['user_id'])
            ->leftJoin('comm_user_info as t2', function ($join) {
                $join->on('t2.id', '=', 't1.user_id');
            })
            ->orderBy('t2.user_tariff_code', 'DESC')
            ->orderBy('t2.create_time', 'DESC')
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }

    //我推广的人数
    public function getMyPromotionCount($request){
        return DB::table('team_relation as t1')
            ->select('t1.id', 't1.user_id' ,'t1.create_time' ,'t2.headimgurl', 't2.login_name', 't2.user_tariff_code', 't2.user_name')
            ->where('t1.parent1', $request['user_id'])
            ->where('t1.user_id', '!=', $request['user_id'])
            ->leftJoin('comm_user_info as t2', function ($join) {
                $join->on('t2.id', '=', 't1.user_id');
            })
            ->count();
    }

    //查询直推人数
    public function getRecommendCount($user_id)
    {
        return $this->model
            ->where('user_id', '!=', $user_id)
            ->where('parent1', $user_id)
            ->count();
    }

    //查询团队关系
    public function getRelation($user_id)
    {
        $ret = optional($this->model
            ->select(
                'id',
                'user_id',
                'parent1',
                'parent2',
                'parent3',
                'parent4',
                'parent5',
                'parent6',
                'parent7',
                'parent8',
                'parent9',
                'parent10'
            )
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->first())
            ->toArray();
        return $ret;
    }

    //更新推荐关系
    public function updateRecommendRela($user_id, $recommendId)
    {
        return DB::select('UPDATE team_relation as t1, (select team_user_level from team_relation where user_id = \''.$recommendId.'\' ) as t2   
                SET t1.team_user_level = CONCAT(\''.$recommendId. ',' . '\',ifnull(t2.team_user_level,""))
                WHERE t1.user_id = \''.$user_id.'\'');
    }

    //更新用户直推,间推,顶推
    public function updateThreeInfo($user_id, $data)
    {
        return $this->model->where('user_id', $user_id)->update($data);
    }

    //查询当前用户所有推荐的用户信息
    public function getMyRecommendInfo($user_id)
    {
        $ret = optional($this->model
//                    ->select('id','user_id')
            ->select('user_id', 'user_name')
            ->where('team_user_level', 'like', '%'.$user_id.'%')
            ->get())
            ->toArray();
        return $ret;
    }

    //查询上级信息
    public function getSuperiorInfo($user_id)
    {
        $ret = DB::table('team_relation as t1')
            ->select('t2.user_id', 't2.user_name', 't2.login_name')
            ->where('t1.user_id', $user_id)
            ->leftJoin('comm_user_info as t2', function ($join) {
                $join->on('t2.id', '=', 't1.parent1');
            })
            ->first();
        return $ret;
    }

    /**
     * 查询我直推的用户
     */
    public function getMyPush($user_id)
    {
        $ret = optional($this->model
            ->select('user_id', 'user_name')
            ->where('parent1', $user_id)
            ->get())
            ->toArray();
        return $ret;
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

    //团队用户
    public function getTeamListUser($request)
    {
        $ret = optional(DB::table('team_relation as t1')
            ->select('t2.user_id', 't2.user_name', 't2.login_name', 't2.user_tariff_code', 't2.create_time')
            ->where('t1.team_user_level', 'like', '%'.$request['user_id'].'%')
            ->where('t2.user_tariff_code', $request['level_name'])
            ->leftJoin('comm_user_info as t2', function ($join) {
                $join->on('t2.id', '=', 't1.user_id');
            })
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }

    //更新自身等级关系
    public function updateSelfRelation($user_id, $data)
    {
        return $this->model
            ->where('user_id',$user_id)
            ->update($data);
    }
    
    //更新下级等级关系
    public function updateLevelRelation($user_id, $now_level, $field)
    {
        $str = '';
        for ($i = substr($now_level, -1);$i <= (substr($field, -1)-1);$i++) {
            $level = "parent".$i;
            $str .=  $level ." = CASE WHEN ".$level . "<= '$user_id' THEN 0 WHEN " .$level ." THEN ".$level." END,";
        }
        $where = '';
        for ($i = substr($field, -1);$i <= 8;$i++) {
            $where .= "parent"."$i <= '$user_id' AND ";
        }

        $sql = "UPDATE team_relation SET $str $field = '$user_id' where $where team_user_level like concat('%','$user_id','%')";
        return DB::select($sql);
    }

    //查询直推我的 (直推我的人)A
    public function getABCRecommend($user_id, $parent)
    {
        $sql = "SELECT t2.user_id, t2.user_name, t2.login_name, t2.user_tariff_code, t2.create_time FROM team_relation AS t1 LEFT JOIN comm_user_info AS t2 ON t2.user_id = t1.".$parent." WHERE t1.user_id = ".$user_id." ";
        return DB::select($sql);
    }

    /**
     * @desc 查询推荐我的三级 (全部) A,B,C  parent1,parent2,parent3
     * @param string user_id 用户ID
     */

    public function getABCRecommendAll($user_id){
        $ret = DB::select("SELECT parent1,parent2,parent3,user_id FROM team_relation WHERE user_id  = '$user_id' ");
        return json_decode(json_encode($ret[0]),true);
    }

    /**
     * 获取用户的所有上级推荐用户
     * 返回所有数据，按照更新时间倒叙
     * @param string user_id 用户ID
     */
    public function getSuperiorRecommendAll($user_id){
        $ret = DB::select("SELECT 
            t0.id,t0.user_id,t0.user_name,t0.status,t0.login_name,t0.level_id,t0.user_tariff_code,t0.level_name
            FROM comm_user_info AS t0
            WHERE FIND_IN_SET(t0.user_id,(SELECT tr.team_user_level FROM team_relation AS tr  WHERE tr.user_id  = '$user_id' ))
            ORDER BY t0.id DESC ");
        return json_decode(json_encode($ret),true);
    }
    public function checkRelation($userId)
    {
        $ret = DB::select("SELECT 
            t0.user_id,t0.level_name,t0.user_tariff_code
            FROM comm_user_info AS t0
            WHERE FIND_IN_SET(t0.user_id,(SELECT tr.team_user_level FROM team_relation AS tr  WHERE tr.user_id  = '$userId' ))
            ORDER BY t0.id DESC ");
        return json_decode(json_encode($ret),true);
    }

    /**
     * 获取用户上级
     */
    public function judgeRecommendRelition($request){

        $ret = optional(DB::table('comm_user_info as t0')
            ->select('t0.login_name','t0.user_id')
            ->leftJoin('team_relation as t1',function ($join){
                $join->on('t0.user_id', '=', 't1.parent1');
            })
            ->where('t1.user_id',$request['user_id'])
            ->get())
            ->toArray();
        return isset($ret[0]) ? $ret[0] : [];
    }
}