<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsInfo extends Model
{
    protected $table = "goods_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getImgAttribute($value){

        return R($value,false);

    }
    public function getImgListAttribute($value){

        return R($value,false);

    }

    public function businessInfo(){
        return $this->hasOne(Store::class,'id','store_id')->select('id','name');
    }
}