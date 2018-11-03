<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:24
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class UpdateBrief extends Model
{
    protected $table = "update_brief";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}