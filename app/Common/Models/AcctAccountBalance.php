<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:46
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class AcctAccountBalance extends Model {

    protected $table = "acct_account_balance";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


}