<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/1
 * Time: 9:19
 */
namespace App\Common\Models;

use App\Modules\Access\Access;
use Illuminate\Database\Eloquent\Model;

class CommNotice extends Model {

    protected $table = "comm_notice";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    //时间处理
    public function getUpdateTimeAttribute($value)
    {
        return date('Y-m-d',strtotime($value));
//        return Access::service('CommonService')
//            ->with('time',$value)
//            ->run('transTime');
//        $time = strtotime($value);
//        $nowtime = time();
//        $difference = $nowtime - $time;
//
//        switch ($difference) {
//
//            case $difference <= '60' :
//                $msg = '刚刚';
//                break;
//
//            case $difference > '60' && $difference <= '3600' :
//                $msg = floor($difference / 60) . '分钟前';
//                break;
//
//            case $difference > '3600' && $difference <= '86400' :
//                $msg = floor($difference / 3600) . '小时前';
//                break;
//
//            case $difference > '86400' && $difference <= '2592000' :
//                $msg = floor($difference / 86400) . '天前';
//                break;
//
//            case $difference > '2592000' &&  $difference <= '7776000':
//                $msg = floor($difference / 2592000) . '个月前';
//                break;
//            case $difference > '7776000':
//                $msg = '很久以前';
//                break;
//        }
//
//        return $msg;
    }


}