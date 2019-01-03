<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/20
 * Time: 16:22
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}