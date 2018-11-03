<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/10
 * Time: 8:55
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class CommPushTemplet extends Model
{
    protected $table = "comm_push_templet";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}