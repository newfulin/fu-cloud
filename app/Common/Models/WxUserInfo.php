<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 16:27
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class WxUserInfo extends Model
{
    protected $table = "wx_user_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //特殊符号 emoji表情转义
    public function setNickNameAttribute($value){
        Log::info('昵称转义-------------------------');
        if(!is_string($value))return $value;
        if(!$value || $value == 'undefined')return '';

        $text = json_encode($value); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }

    //微信 特殊昵称处理 emoji 处理
    public function getNickNameAttribute($value){
        $text = json_encode($value); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }
}