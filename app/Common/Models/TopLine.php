<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/1
 * Time: 12:01
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class TopLine extends Model {

    protected $table = "erp_top_line";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getAttr1Attribute($value)
    {
        if($value){
            return R($value,false);
        }
    }

    public function getAttr2Attribute($value)
    {
        if($value){
            return R($value,false);
        }
    }

    public function getAttr3Attribute($value)
    {
        if($value){
            return R($value,false);
        }
    }

    public function getAuthorImgAttribute($value)
    {
        if($value){
            return R($value,false);
        }
    }


}