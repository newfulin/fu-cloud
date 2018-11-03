<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 13:48
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class CommSupportBankInfo extends Model{
    protected $table = "comm_support_bank_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}