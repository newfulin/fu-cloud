<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:44
 */

namespace App\Modules\Callback\Listeners ;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class QueueListener implements ShouldQueue {

    public function handle($request)
    {
        Log::info("QueueListener:".json_encode($request));

    }
}