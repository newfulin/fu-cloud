<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 11:55
 */
namespace App\Handlers;

use Illuminate\Support\Facades\Log;

class SwooleTimerHandler {

    //定时器
    public function onTimer($timer)
    {
        echo "tick-2000ms\n";
        Log::info('Swoole onTimer:'.$timer);
    }

}