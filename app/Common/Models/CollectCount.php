<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/16
 * Time: 14:56
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class CollectCount extends Model
{
    protected $table = "collect_count";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'type',
        'user_id',
        'obj_id',
        'status',
        'create_time',
        'create_by',
        'update_time'
    ];
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}