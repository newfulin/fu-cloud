<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 18:15
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TranOrder extends Model
{
    protected $table = "tran_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}