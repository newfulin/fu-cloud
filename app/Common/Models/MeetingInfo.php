<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class MeetingInfo extends Model
{

    protected $table = "meeting_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

//    public function getShowImgAttribute($value)
//    {
//        if($value){
//            return R($value);
//        }
//        return $value;
//    }
//
//    public function getLecturerImgAttribute($value)
//    {
//        if($value){
//            return R($value);
//        }
//        return $value;
//    }
}