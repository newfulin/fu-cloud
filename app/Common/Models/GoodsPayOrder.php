<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 16:22
 * 微信流水表
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class GoodsPayOrder extends Model
{
    protected $table = "goods_pay_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}