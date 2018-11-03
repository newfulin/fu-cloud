<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 10:03
 */

namespace App\Common\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class WeChatShare extends Model
{
    protected $table = "wechat_share";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getShareLargeUrlAttribute($value){
        if($value){
            return R($value,false);
        }
        return $value;
    }

    public function getShareLittleUrlAttribute($value)
    {
        if($value){
            return R($value,false);
        }
        return $value;
    }
}