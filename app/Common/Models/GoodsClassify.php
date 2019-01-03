<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/6
 * Time: 12:54
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class GoodsClassify extends Model
{

    protected $table = "goods_classify";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    public function getImgAttribute($value)
    {
        return R($value,false);
    }
}