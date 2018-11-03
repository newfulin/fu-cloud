<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 13:56
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class ClickCount extends Model
{
    protected $table = "click_count";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'type',
        'obj_id',
        'user_id',
        'create_time',
        'create_by',
        'update_time'
    ];
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    public $timestamps = true;
}