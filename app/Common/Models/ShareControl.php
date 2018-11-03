<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/30
 * Time: 16:53
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class ShareControl extends Model
{
    protected $table = "share_control";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}