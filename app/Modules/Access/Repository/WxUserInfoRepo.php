<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 18:33
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\WxUserInfo;

class WxUserInfoRepo extends Repository
{
    public function __construct(WxUserInfo $model)
    {
        $this->model = $model;
    }

    public function getWxInfo($user_id){
        return optional($this->model
            ->select('unionid','openid')
            ->where('user_id',$user_id)
            ->first()
            )->toArray();
    }
    public function update($userId, $attributes)
    {
        $attributes['nickname'] = $this->model->setNickNameAttribute($attributes['nickname']);
        return $this->model->where('user_id',$userId)->update($attributes);
    }
}