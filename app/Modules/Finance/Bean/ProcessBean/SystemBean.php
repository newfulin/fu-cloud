<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 16:02
 */

namespace App\Modules\Finance\Bean\ProcessBean;

use Illuminate\Support\Facades\Log;



class SystemBean {

    public function handle($request)
    {
        Log::debug("getProcessId.SystemBean.handle...");
        $template = $request['book']['template'];
        return $template['process_id'];
    }
}