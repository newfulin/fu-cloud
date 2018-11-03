<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:38
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class SixCarCommUserInfo extends Model
{
    protected $table = "comm_user_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}