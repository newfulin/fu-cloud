<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/22
 * Time: 11:29
 */

namespace Nxp\Team\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CommUserInfo;
use Illuminate\Support\Facades\DB;

class NxpCommUserInfo extends Repository {

    public function __construct(CommUserInfo $model)
    {
        $this->model = $model;
    }

    public function getUser($user_id){
        $ret = optional($this->model
            ->select('id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','account_name','open_bank_name','account_no','agent_id','parner_id',
                'regist_address','bank_line_name','bank_reserved_mobile','level_name','bank_code','store_id','cash_status')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
        return $ret;
    }
    public function getLevelName($userId)
    {
        $ret = optional($this->model
            ->select('level_name')
            ->where('user_id',$userId)
            ->first())
            ->toArray();
        return $ret;
    }

    //根据手机号获取用户ID
    public function getUserIdByMobile($mobile){
        return optional($this->model
            ->select('user_id')
            ->where('login_name',$mobile)
            ->first())
            ->toArray();
    }
}