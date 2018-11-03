<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 13:56
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class IndexImg extends Model
{
    protected $table = "index_img";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    public $timestamps = true;
}