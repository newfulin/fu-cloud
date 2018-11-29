<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 15:03
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CommUserInfo;
use App\Modules\Transfer\Transfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommUserRepo extends Repository
{
    public function __construct(CommUserInfo $model)
    {
        $this->model = $model;
    }
    // 通过用户ID获取用户等级level_name(资费编码user_tariff_code)
    public function getUserLevelById($userId)
    {
        $ret = optional($this->model
            ->select('level_name')
            ->where('user_id',$userId)
            ->first())
            ->toArray();
        return $ret['level_name'];
    }
    //根据ID查询用户信息
    public function getUser($user_id){
        return optional($this->model
            ->select('id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','account_name','open_bank_name','cash_status','account_no','agent_id','parner_id','regist_address','bank_line_name','bank_reserved_mobile','level_name','bank_code','store_id','headimgurl')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }

    //根据手机号查询用户信息
    public function getUserByLoginName($tel)
    {
        $ret = optional($this->model
            ->where('login_name',$tel)
            ->first())
            ->toArray();
        return $ret;
    }

    //根据手机号获取用户资费编码
    public function getCodeByTel($tel)
    {
        $ret = optional($this->model
            ->select('user_id','user_tariff_code')
            ->where('login_name',$tel)
            ->first())
            ->toArray();
        return $ret['user_tariff_code'];
    }

    //更新用户信息
    public function updateUser($user_id,$data)
    {
        return $this->model->where('user_id',$user_id)->update($data);
    }

    public function update($id, $attributes)
    {
        return $this->model->where('id',$id)->update($attributes);
    }

    //修改密码
    public function updateUserPass($login_name,$data)
    {
        return $this->model->where('login_name',$login_name)->update($data);
    }

    //根据用户名 , 用户等级  查询合伙人信息
    public function getUserByUserName($username,$code)
    {
        return optional($this->model
            ->where('user_name',$username)
            ->where('user_tariff_code',$code)
            ->first())
            ->toArray();
    }

    //查询合伙人
    public function getUserByCode($code)
    {
//        $ret = DB::table('comm_user_info')
//                ->select('id','user_name')
//                ->where('user_tariff_code',$code)
//                ->orderBy('create_time','asc')
//                ->chunk(10,function($users){
//                    foreach ($users as $key => $val){
//                        Log::info($key);
//                    }
//                });
//        dd($ret);
        return optional($this->model
            ->select('id','user_name','login_name')
            ->where('user_tariff_code',$code)
            ->get())
            ->toArray();
    }

    public function getTest($code)
    {
        return optional($this->model
            ->select('user_name')
            ->where('user_tariff_code',$code)
            ->orderBy('login_name','desc')
            ->get())
            ->toArray();
    }

    public function getUserByStatus($status = 60)
    {
        $this->model
            ->select('user_id','user_name','login_name','status','old_user_id')
            ->where('status',$status)
            ->where('old_user_id','!=',null)
            ->where('old_user_id','!=','')
            ->chunk(100,function ($users){
                $userInfo = optional($users)->toArray();
                Log::info('实名用户 -》');

                return Transfer::service('NO6Service')
                    ->with('data',$userInfo)
                    ->run('updateUserInfo');

            });
    }

    /**
     * @desc 根据unionid 获取用户信息
     * @return mixed
     */
    public function getUserInfoByUnionid($request){
        $ret = DB::table('comm_user_info as t0')
                ->select('t0.id','t0.user_id','t0.user_name','t0.user_type','t0.status','t0.login_name','t0.level_id','t0.merc_id','t0.client_id','t0.create_time','user_tariff_code','t0.crp_nm','t0.crp_id_no','t0.account_name','t0.open_bank_name','t0.account_no','t0.agent_id','t0.parner_id','regist_address','t0.bank_line_name','t0.bank_reserved_mobile','t0.level_name','t0.bank_code','t0.store_id','t0.headimgurl','t1.openid','t1.headimgurl','t1.nickname')
                ->leftJoin('wx_user_info as t1',function($join){
                    $join->on('t0.unionid', '=', 't1.unionid');
                })
                ->where('t1.unionid',$request['unionid'])
                ->first();
        return json_decode(json_encode($ret),true);
    }

    /**
     * @desc 根据openid 获取用户信息
     */
    public function getUserInfoByOpenId($request){
        $ret = DB::table('comm_user_info as t0')
            ->select('t0.id','t0.user_id','t0.user_name','t0.user_type','t0.status','t0.login_name','t0.level_id','t0.merc_id','t0.client_id','t0.create_time','user_tariff_code','t0.crp_nm','t0.crp_id_no','t0.account_name','t0.open_bank_name','t0.account_no','t0.agent_id','t0.parner_id','regist_address','t0.bank_line_name','t0.bank_reserved_mobile','t0.level_name','t0.bank_code','t0.store_id','t0.headimgurl','t1.openid','t1.headimgurl','t1.nickname')
            ->leftJoin('wx_user_info as t1',function($join){
                $join->on('t0.user_id', '=', 't1.user_id');
            })
            ->where('t1.openid',$request['openid'])
            ->first();
        return json_decode(json_encode($ret),true);
    }

    public function getReferralCode($request){

        if(!$request['recommend_code']) return '';

        return optional($this->model
            ->select('id','user_tariff_code')
            ->where('referral_code',$request['recommend_code'])
            ->first()
        )->toArray();
    }

    //根据ID查询用户信息
    public function getUserById($user_id){
        return optional($this->model
            ->select('user_id','user_name','user_tariff_code')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }

    //获取 总代理,合伙人,区代 用户
    public function getUserDataByLevelName(){
        return optional($this->model
            ->select('user_id','user_name')
            ->where('level_name','>=','P1301')
            ->where('level_name','<=','P1401')
            ->get())
            ->toArray();
    }
}