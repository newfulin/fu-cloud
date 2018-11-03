<?php
/**
 * Created by VSCode.
 * User: satsun
 * Date: 2018/2/9
 * Time: 17:35
 */

namespace App\Modules\Finance\Bean\Amountbean;

use Illuminate\Support\Facades\Log;


/**
 * 获取 剩余利润
 */
class RemainProfit {

    public function handle($request)
    {
        Log::debug("RemainProfit.handle...");
        //计算借贷总额
        $result = Money()->format('0.00');
        $totalDebitAmount  = Money()->format('0.00') ;  //借方合计金额
        $totalCreditAmount = Money()->format('0.00') ; //贷方合计金额
        $template = $request['book']['template'];
        $booking_order = $request['book']['booking_order'];
        foreach ($booking_order as $key => $value) {
            //不包含自己
            if ($template['voucher_batch_id'] != $value['batch_detail_id']) {
                $totalDebitAmount  = Money()->calc($totalDebitAmount,"+",$value['debit_amount']) ;
                $totalCreditAmount = Money()->calc($totalCreditAmount,"+",$value['credit_amount']) ;
            }
        }
        if ($template['debit_credit_direction'] == 1) {
            $result = Money()->calc($totalCreditAmount,'-',$totalDebitAmount);
        } else {
            $result = Money()->calc($totalDebitAmount,'-',$totalCreditAmount);
        }
        //四舍五入留2位小数
        return Money()->format($result);
    }

}