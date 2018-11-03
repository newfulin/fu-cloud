<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 用户资费表
 */
class AcctUserTariffRate extends Model {
    protected $table = "acct_user_tariff_rate";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

}
