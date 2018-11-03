<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 17:51
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommSms;

class CommSmsRepo extends Repository
{
    public function __construct(CommSms $model)
    {
        $this->model = $model;
    }

    public function getMobileCaptcha($mobile)
    {
        $ret = optional($this->model
            ->select('id','mobile','captcha','create_time')
            ->where('mobile',$mobile)
            ->orderBy('create_time','desc')
            ->first())
            ->toArray();
        return $ret;
    }

    //获取当天数据
    public function getCountByMobile($mobile)
    {
        return $this->model
            ->select('*')
            ->where('mobile',$mobile)
            ->whereDate('create_time', date('Y-m-d'))
            ->count();
    }
}