<?php
/**
 *
 */
namespace App\Modules\Finance\Middleware\Process;


class SystemBean extends Process {

    /**
     * 获取父类记账单明细
     */
    public function getBookingOrder($request)
    {
        //$policy = $request['policy'];
        $parBookingOrder = parent::getBookingOrder($request);
        return $parBookingOrder;
    }
}