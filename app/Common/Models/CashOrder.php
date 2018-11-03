<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 交易流水汇总表
 */
class CashOrder extends Model {



    protected $table = "cash_order";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}
