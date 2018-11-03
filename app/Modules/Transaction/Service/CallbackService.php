<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 10:07
 */
namespace App\Modules\Transaction\Service ;

use App\Common\Contracts\Service;

class CallbackService extends Service {
    /**
     * @return mixed
     */
    public function getRules()
    {
        return [];
    }

    public function handle($request)
    {
        //这处理返回的报文
        return $request;
    }

}