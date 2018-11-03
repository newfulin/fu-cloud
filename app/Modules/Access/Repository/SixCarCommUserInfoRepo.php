<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:40
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\SixCarCommUserInfo;
use Illuminate\Database\Eloquent\Model;

class SixCarCommUserInfoRepo extends Repository
{
    public function __construct(SixCarCommUserInfo $model)
    {
        $this->model = $model;
    }

    //根据ID查询用户信息
    public function getUser($user_id){
        return optional($this->model
            ->select('id','user_id','user_name','user_type','status','login_name','level_id','merc_id','client_id','create_time',
                'user_tariff_code','crp_nm','crp_id_no','account_name','open_bank_name','cash_status','account_no','agent_id','parner_id','regist_address','bank_line_name','bank_reserved_mobile','level_name','bank_code','store_id')
            ->where('user_id',$user_id)
            ->first())
            ->toArray();
    }
}