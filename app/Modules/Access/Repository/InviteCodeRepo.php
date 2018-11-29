<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 11:07
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\InviteCode;
use Illuminate\Database\Eloquent\Model;

class InviteCodeRepo extends Repository
{
    public function __construct(InviteCode $model)
    {
        $this->model = $model;
    }
    public function getCodeNum($userId)
    {
        $ret = $this->model
            ->select('id')
            ->where('user_id',$userId)
            ->count();
        return $ret;
    }
    //通过用户id获取邀请码列表
    public function getCodeInfoByUserId($request)
    {
        return optional($this->model
            ->select('user_id','old_user_id','code','state','level_name','amount','change_state')
            ->where('user_id',$request['user_id'])
            ->where('state','10')
            ->where('level_name',$request['level'])
            ->paginate($request['pageSize']))
            ->toArray();
    }

    //使用激活码，更新状态
    public function updateState($code,$params)
    {
        return $this->model->where('code', $code)->update($params);
    }

    //获取邀请码的信息  内部邀请码
    public function getInfoByCode($code)
    {
        $ret = optional($this->model
            ->select('old_user_id','level_name','change_state','user_id','amount','state','effective_time')
            ->where([
                'code' => $code,
                'type' => '10'
            ])
            ->first())
            ->toArray();
        return $ret;
    }

    //获取邀请码的信息   外部邀请码
    public function getInfoByCode20($code)
    {
        $ret = optional($this->model
            ->select('old_user_id','level_name','change_state','user_id','amount','state')
            ->where([
                'code' => $code,
                'type' => '20'
            ])
            ->first())
            ->toArray();
        return $ret;
    }

    //用户邀请码检测
    public function checkUserCode($code)
    {
        $ret = optional($this->model
            ->select('state')
            ->where('code',$code)
            ->where('state','20')
            ->first())
            ->toArray();
        return $ret;
    }

    //用户邀请码转赠
    public function giveInviteCode($code,$params)
    {
        return $this->model->where('code', $code)->update($params);
    }

    //添加
    public function insertInfo($data)
    {
        return optional($this->model->insert($data))
            ->toArray();
    }

}