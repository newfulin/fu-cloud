<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 17:42
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class TranTransOrder extends Model
{
    protected $table = "tran_trans_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}