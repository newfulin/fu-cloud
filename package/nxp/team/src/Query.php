<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/22
 * Time: 10:53
 */

namespace Nxp\Team;

use App\Modules\Access\Service\CommonService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nxp\Team\Repository\NxpCommUserInfo;
use Nxp\Team\Repository\NxpTeamRetionRepo;

class Query{
    public $team;
    public $user;

    public function __construct(NxpTeamRetionRepo $team,NxpCommUserInfo $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * @desc 查询我的推广
     * 使用上级ID 可以直接 查询到所有归属于 上级ID的用户
     * @params user_id string 用户ID
     * @params page number 页码
     * @params pageSize number 条数
     */
    public function getMyPromotionById($request){
        $ret = $this->team->getMyPromotion($request);

        $ret =  $this->cutOutData($ret,false);

        foreach ($ret as $key => $value){
            $ret[$key]->rank = config('const_user.'.$value->user_tariff_code . '.code');
            $ret[$key]->level_name = config('const_user.'.$value->user_tariff_code . '.msg');
            if(!$value->headimgurl) $ret[$key]->headimgurl = R('webimg/head/head.png');
            $ret[$key]->teamnum = $this->team->getRecommendCount($value->user_id);
        }
        return $ret;
    }

    /**
     * @desc 我的推广的人数
     */
    public function getMyPromotionCount($request){
        return $this->team->getMyPromotionCount($request);
    }

    /**
     * @desc 我的团队等级列表
     * @params user_id string 用户ID
     */
    public function getListLevel($request)
    {
        $userInfo = $this->user->getUser($request['user_id']);

        $field = config('const_user.'.$userInfo['user_tariff_code'].'.field');

        return $this->getLevelName($field);
    }

    public function getLevelName($field)
    {
        $len = substr($field,6);
        $arr = array();
        for($i = 4;$i < $len;$i++){
            $parent = 'parent'.$i;
            $arr[$i]['title'] = config('const_user.'.$parent.'.msg');
            $arr[$i]['level_name'] = config('const_user.'.$parent.'.code');
            $arr[$i]['status'] = '0';
            //方便前台处理数据
            $arr[$i]['arr'] = [];
        }
        array_multisort(array_column($arr,'level_name'),SORT_DESC,$arr);
        return $arr;
    }

    /*
     * 团队用户,XX等级的直推用户
     * @param string user_id 用户ID
     * @param string level_name 等级
     * @params page number 页码
     * @params pageSize number 条数
     */
    public function getTeamListUser($request)
    {
        $ret = $this->team->getTeamListUser($request);
        foreach ($ret as $key => $val){
            $ret[$key]->teamnum = $this->team->getRecommendCount($val->user_id);
            $user_name = app()->make(CommonService::class)->with('str',$val->user_name)->run('userTextDecode');
            $ret[$key]->user_name = $this->C($user_name);
            $ret[$key]->login_name = $this->Mobile($val->login_name);
            $ret[$key]->level_medal = config('const_user.'.$val->user_tariff_code.'.code');
            $ret[$key]->create_time = date('Y-m-d',strtotime($val->create_time));
        }

        return $ret;
    }

    /*
     * 更新自身等级关系
     */
    public function updateSelfRelation($user_id,$data){
        return $this->team->updateSelfRelation($user_id,$data);
    }

    /*
     * 更新下级等级关系
     */
    public function updateLevelRelation($request){
        return $this->team->updateLevelRelation($request['user_id'],$request['now_level'],$request['field']);
    }

    /**
    * 查询推荐我的三级 ABC
    * @param string user_id 用户ID
    * @param string parent 推荐级别
    */
    public function getABCRecommend($request){
        return $this->team->getABCRecommend($request['user_id'],$request['parent']);
    }

    /**
     * 查询推荐我的三级 (全部) A,B,C  parent1,parent2,parent3
     * @param string user_id 用户ID
     */
    public function getABCRecommendAll($request){
        return $this->team->getABCRecommendAll($request['user_id']);
    }

    /**
     * 获取用户的所有上级推荐用户
     * 返回所有数据，按照更新时间倒叙
     * @param string user_id 用户ID
     */
    public function getSuperiorRecommendAll($request){
        $teamInfo = $this->team->getSuperiorRecommendAll($request['user_id']);

        $userInfo = $this->user->getUser($request['user_id']);
        
        $levelStr = $this->handelStr(config('const_user.LEVEL'),$userInfo['level_name']);

        $level = array_filter(explode(',',$levelStr));
        
        $arr = [];
        foreach($level as $key => $val){
            $arr[] = $this->getLevelUserInfo($teamInfo,$val);
        }
        array_multisort(array_column($arr,'user_tariff_code'),SORT_ASC,$arr);
        return $arr;
        
    }

    public function checkRelation($userId)
    {
        $teamInfo = $this->team->checkRelation($userId);
        $userInfo = $this->user->getLevelName($userId);
        $levelStr = $this->handelStr(config('const_user.LEVEL'),$userInfo['level_name']);
        $level = array_filter(explode(',',$levelStr));
        $arr = [];
        foreach($level as $key => $val){
            $arr[] = $this->getLevelUserInfo($teamInfo,$val);
        }
        return $arr;

    }

    public function getLevelUserInfo($retUserInfo,$level)
    {
        foreach ($retUserInfo as $key => $userInfo ){
            $user_tariff_code = $userInfo['user_tariff_code'];
            if($user_tariff_code == $level){
                return $userInfo;
            }
        }
        return array('user_id'=>'0','user_tariff_code'=>$level);
    }

    //判断用户有无推荐关系
    public function judgeRecommendRelition($request){
        return $this->team->judgeRecommendRelition($request);
    }

    //团队用户切换
    public function switchTeamRelations($request){
        $teamInfo = $this->team->getRelation($request['recommend_id']);

        $data = [
            'parent1' => $request['recommend_id'],
            'parent2' => $teamInfo['parent1'],
            'parent3' => $teamInfo['parent2'],
        ];

        DB::beginTransaction();
        try {
            $this->team->updateThreeInfo($request['user_id'],$data);
            //更新推荐关系
            $this->team->updateRecommendRela($request['user_id'],$request['recommend_id']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('数据修改失败', 9999);
        }

    }




    /**
     * 更改时间格式
     */
    public function cutOutData($ret,$flag = true)
    {
        foreach($ret as $key => $val){
            $ret[$key]->create_time = date('Y-m-d',strtotime($val->create_time));
            $user_name = app()->make(CommonService::class)->with('str',$val->user_name)->run('userTextDecode');
            $ret[$key]->user_name = $user_name;
            if($flag){
                $ret[$key]->login_name  = substr($val->login_name, 0, 5).'****'.substr($val->login_name, 9);
                $ret[$key]->user_name = $this->C($user_name);
            }
        }
        return $ret;
    }

    /**
     * 名字 * 处理
     */
    public function C($str){
        $length = mb_strlen($str,'UTF8');
        if($length<=0)  return '*';

        $first = mb_substr($str,0,1,'utf-8') . '*';
        $last  = '';
        if($length >= 3) {
            $last  = mb_substr($str, -1, 1,'utf-8');
        }

        return $first . $last;
    }

    /*
     * 手机号 * 处理
     */
    public function Mobile($value){
        $prefix = substr($value,0,3);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix."****".$suffix;
    }

    //截取指定后的字符串
    public function handelStr($arr,$str){

        $level = implode(',',$arr);
        $newString = strstr($level, $str);
        $length = strlen($str);
        return substr($newString, $length);
    }
}