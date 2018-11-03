<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 17:15
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityManage extends Model {

    protected $table = "activity_manage";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


}