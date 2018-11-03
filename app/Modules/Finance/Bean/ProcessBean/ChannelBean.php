<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 16:02
 */

namespace App\Modules\Finance\Bean\ProcessBean;

use Illuminate\Support\Facades\Log;



class ChannelBean {

    public function handle($request)
    {
        Log::debug("ChannelBean.handle...");
        $template = $request['book']['template'];
        $channel_id = $request['channelMerc']['channel_id'];
        return $channel_id;
    }
}