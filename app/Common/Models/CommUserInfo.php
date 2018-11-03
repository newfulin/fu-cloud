<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 08:44
 */

namespace App\Common\Models;


use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

// implements AuthenticatableContract, AuthorizableContract
class CommUserInfo extends Model
{

//    use Authenticatable, Authorizable;

    protected $table = "comm_user_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    //JSON 隐藏
    protected $hidden = [
        'password',
    ];
    protected $fillable=[
        'id'
    ];
//    protected $fillable;  mass 数组实例化白名单
//    protected $guarded;   mass 数组实例化黑名单


    public function setUserTariffCodeAttribute($value){
        return '1';
    }
//    public function getUserTariffCodeAttribute(){
//        $value = $this->attributes['user_tariff_code'];
//        return substr($value , 1);
//
//    }


    //身份证号处理
    public function getCrpIdNoAttribute($value)
    {
        //截取身份证号前6位
        $prefix = substr($value,0,6);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix." **** **** **** ".$suffix;
    }

    //银行卡号处理
    public function getAccountNoAttribute($value)
    {
        //截取银行卡号后4位
        $suffix = substr($value,-4,4);
        return " **** **** **** ".$suffix;
    }

    //手机号处理
    public function getBankReservedMobileAttribute($value)
    {
        $prefix = substr($value,0,3);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix."****".$suffix;
    }

    //头像图片
    public function getHeadimgurlAttribute($value){
        if(!$value){
            return R('webimg/head/head.png');
        }else if(strpos($value,'upload') !== false){
            return R($value);
        }
        return $value;
    }

    //微信 特殊昵称处理 emoji 处理
    public function getUserNameAttribute($value){
        $text = json_encode($value); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }
}