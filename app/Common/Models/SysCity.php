<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 13:56
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class SysCity extends Model
{
    protected $table = "sys_city";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}