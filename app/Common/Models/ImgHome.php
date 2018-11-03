<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class ImgHome extends Model
{
    protected $table = "img_home";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getBannerAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }
}