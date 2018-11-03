<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 产品信息表
 */
class PriceProduct extends Model {



    protected $table = "price_product";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}
