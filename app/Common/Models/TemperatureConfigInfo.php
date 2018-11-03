<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 14:19
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TemperatureConfigInfo extends Model
{
    protected $table = "temperature_config_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}