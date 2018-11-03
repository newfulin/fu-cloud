<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 14:40
 */

namespace App\Common\Models;


use App\Modules\Access\Access;
use Illuminate\Database\Eloquent\Model;

class CommPushRecord extends Model
{
    protected $table = "comm_push_record";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getCreateTimeAttribute($value)
    {
        return date('Y-m-d',strtotime($value));
//        return Access::service('CommonService')
//            ->with('time',$value)
//            ->run('transTime');
    }

}