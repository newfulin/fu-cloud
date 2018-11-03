<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 13:24
 */
namespace App\Modules\Transaction\Channel ;


use App\Common\Contracts\Channel;
use App\Modules\Transaction\Request;

class Weixin extends Channel {


    public function handle($request)
    {

        return 'weixin transaction ok';
    }



}

