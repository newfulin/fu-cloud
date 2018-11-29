<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use Illuminate\Support\Facades\DB;
use App\Common\Models\CommUserInfo;
use App\Common\Contracts\Repository;
/**
 * 用户信息
 */
class CommUserInfoRepository extends Repository {

    public $model;

    public function __construct(CommUserInfo $model)
    {
        $this->model = $model;
    }

    /**
     * 获取用户明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }

    /**
     * 获取用户明细信息根据登录账户
     */
    public function getUserInfoByLoginName($LoginName){
        $ret = $this->model->where('login_name','=',$LoginName)->first();
        return $ret;
    }

    /**
     * 获取所有用户数
     * 查询条件为old_user_id > 0 
     */
    public function getAllUserCount(){
        $ret = $this->model->where('id','>','0')->count();
        return $ret;
    }

    /**
     * 获取所有用户数
     * 查询条件为old_user_id > 0 
     */
    public function getPageData($page){
        $ret = $this->model->where('old_user_id','>','0')->paginate(100, ['*'], 'page', $page);
        return $ret;
    }

    /**
     * 更新数据
     */
    public function update($data,$Id)
    {
        $this->model->where('id','=',$Id)->update($data);
    }

    /**
     * 保存插入数据
     */
    public function save($data)
    {
        $this->model->insert($data);
    }

   /**
     * 获取用户的所有上级推荐用户
     * 返回所有数据，按照更新时间倒叙
     */

    public function getUserByTeam($user_id)
    {
        $ret = DB::select("SELECT 
        t0.id,t0.user_id,t0.user_name,t0.status,t0.login_name,t0.level_id,t0.user_tariff_code,t0.level_name,t1.team_user_level
        FROM comm_user_info AS t0 INNER JOIN team_relation AS t1 ON t0.user_id = t1.user_id
        WHERE FIND_IN_SET(t0.user_id,(SELECT tr.team_user_level FROM team_relation AS tr  WHERE tr.user_id  = '$user_id' ))
        ORDER BY LENGTH(t1.team_user_level) DESC ");
        return json_decode(json_encode($ret),true);
    }

    /**
     * 获取 parent1,parent2,parent3
     */

    public function getTeamRelation($user_id)
    {
        $ret = DB::select("SELECT parent1,parent2,parent3,user_id FROM team_relation WHERE user_id  = '$user_id' ");
        return json_decode(json_encode($ret[0]),true);
    }

}