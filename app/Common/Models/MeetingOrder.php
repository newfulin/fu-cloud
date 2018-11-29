<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 17:31
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class MeetingOrder extends Model
{
    protected $table = "meeting_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}