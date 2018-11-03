<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/1
 * Time: 9:19
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class CommCodeMaster extends Model {

    protected $table = "comm_code_master";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}