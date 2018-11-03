<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/29
 * Time: 10:06
 */

namespace App\Common\Models;


use Illuminate\Database\Eloquent\Model;

class ImgBanner extends Model
{
    protected $table = "img_banner";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getImgUrlAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }
}