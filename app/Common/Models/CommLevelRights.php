<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:11
 */

namespace App\Common\Models;


use App\Modules\Access\Access;
use Illuminate\Database\Eloquent\Model;

class CommLevelRights extends Model
{
    protected $table = "comm_level_rights";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    public function getImgUrlAttribute($value)
    {
        return R($value,false);
    }
}