<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 11:48
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class CommBankDb extends Model
{
    protected $table = "comm_bank_db";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}