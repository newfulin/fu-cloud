<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 11:03
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    protected $table = "invite_code";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}