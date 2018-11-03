<?php
/**
 *
 */
namespace App\Modules\Finance\Middleware\Process ;

use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\RedPacketRepository;

class UserNumber extends Process {
    /**
     * 获取父类记账单明细
     */
    public function getBookingOrder($request)
    {
        //Log::info("比如红包进行特殊处理");
        //$policy = $request['policy'];
        $parBookingOrder = parent::getBookingOrder($request);
        return $parBookingOrder;
    }
}