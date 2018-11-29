<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 08:44
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class MeetingRecord extends Model
{

    protected $table = "meeting_record";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}