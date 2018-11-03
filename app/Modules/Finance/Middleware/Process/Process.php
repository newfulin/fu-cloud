<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 15:12
 */
namespace App\Modules\Finance\Middleware\Process;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;

abstract class Process extends Middleware {

    public function handle($request, Closure $next)
    {
        //Log::info("---------Process----------");
        $template  = array_shift($request['template']);
        //Log::info(json_encode($template));
        $request['book']['template'] =$template ;
        $key = $template['voucher_batch_id'];
        $BookingOrder = $this->getBookingOrder($request);
        if( $BookingOrder!='0')
            $request['book']['booking_order'][$key] = $BookingOrder;
        return $next($request);
    }

    public function getBookingOrder($request){

        $order = [];
        $order['id'] = ID();
        $template = $request['book']['template'];
        $order['batch_id']             = $request['book']['batch_id'];
        $order['batch_detail_id']      = $template['voucher_batch_id'];
        $order['account_id'] = $this->getAccountId($request);
        //记账请求码
        $order['voucher_code']         = $template['voucher_code'];
        // 记账时间
        $order['booking_time']         = date("Y-m-d H:i:s");
        // 总账科目
        $order['general_account_code'] = $template['general_account_code'];
        // 账户类型
        $order['account_type']         = $template['account_type'];
        // 账户对象
        $order['account_object']       = $template['account_object'];
        // 摘要
        $order['remark']               = $template['voucher_name'];
        // 借贷方向
        $order['debit_credit_direction'] = $template['debit_credit_direction'];
        //计算借贷金额
        $order['debit_amount']  = $this->getDebitAmount($request);
        $order['credit_amount'] = $this->getCreditAmount($request);
        //账户
        $order['process_id']    = $this->getProcessId($request,$order['account_id']);
        // 明细科目编码
        // general_account_code + account_object + account_type + process_id
        $order['detail_account_code'] = $order['general_account_code']
                                    . $order['account_object']
                                    . $order['account_type']
                                    . $order['process_id'];
        //外联流水id
        $order['external_con_order']     = $this->getExternOrderId($request);
        //$科目类别码
        $order['account_category']       = $this->getAccountCategory($order['general_account_code']);
        // 记账人
        $order['booking_by']             = $template['booking_by'];
        // 审核人
        $order['approving_by']           = $template['approving_by'];
        // 财务主管
        $order['financia_manager_by']    = $template['financia_manager_by'];
        // 出纳
        $order['cash_by']                = $template['cash_by'];
        // 制单人
        $order['make_by']                = $template['make_by'];
        // 经办人
        $order['operator_by']            = $template['operator_by'];
        // 录入方式
        $order['input_mode']             = $template['input_mode'];
        // 反记账流水
        $order['reset_booking_order']    = null;
        // 反记账原因
        $order['reset_booking_reason']   = null;
        // 科目余额更新状态,未更新
        $order['account_balance_status'] = $this->getCode('BOOK_BALANCE_NO_UPDATE');
        // 余额更新时间
        $order['account_balance_time']   = null ;
        // 记账状态:记账，人工制单时有 制单审核等状态
        $order['account_status']         = $this->getCode('BOOK_ORDER_BOOKING');
        $order['create_time']            = date("Y-m-d H:i:s");
        $order['create_by']              = 'system';
        $order['update_time']            = date("Y-m-d H:i:s");
        $order['update_by']              = 'system';

        $debit_amount  = Money()->format($order['debit_amount']);
        $credit_amount = Money()->format($order['credit_amount']);
        return $order;
        //......
        //如果本次 金额为0
    }
    /**
     * 获取账套号
     */
    public function getAccountId($request){
        $template = $request['book']['template'];
        $account_id = $template['account_id'];
        $accountBean = Config::get('finance.account_bean.'.$account_id);
        return app()->make($accountBean)->handle($request);
    }
    /**
    * 获取账户
    */
    public function getProcessId($request,$account_id){
        $template = $request['book']['template'];
        $process_bean = $template['process_bean'];
        $accountBean = Config::get('finance.process_bean.'.$process_bean);
        return app()->make($accountBean)->handle($request);
    }
    /**
     * 计算借方金额
     */
    public function getDebitAmount($request){
        $template = $request['book']['template'];
        $debit_credit_direction = $template['debit_credit_direction'];
        $debit_amount = 0;
        if ($debit_credit_direction == '1') {//借方:debit
            if ($template['debit_amount']&&$template['debit_amount']>0) { //有明确借方值
                $debit_amount = $template['debit_amount'];
            } else {
                $process_bean = $template['debit_amount_bean'];
                //Log::debug($process_bean);
                $debitAmountBean = Config::get('finance.amount_bean.'.$process_bean);
                Log::debug("借方金额::Bean->".json_encode($debitAmountBean));
                $debit_amount = app()->make($debitAmountBean)->handle($request);
            }
        }
        return Money()->format($debit_amount);
    }
    /**
     * 计算贷方金额
     */
    public function getCreditAmount($request){
        $template = $request['book']['template'];
        $debit_credit_direction = $template['debit_credit_direction'];
        $credit_amount = 0;
        if ($debit_credit_direction == '2') {//贷方:debit
            if ($template['credit_amount']&&$template['credit_amount']>0) { //有明确贷方值
                $credit_amount = $template['credit_amount'];
            } else {
                $process_bean = $template['credit_amount_bean'];
                //Log::debug($process_bean);
                $creditAmountBean = Config::get('finance.amount_bean.'.$process_bean);
                Log::debug("贷方金额::bean->".json_encode($creditAmountBean));
                $credit_amount = app()->make($creditAmountBean)->handle($request);
            }
        }
        return Money()->format($credit_amount);
    }

    public function getExternOrderId($request){
           return $request['externOrderId']?$request['externOrderId']:$request['orderId'];
    }

    /**
     * 科目类别码   
    'CATEGORY_ASSET'           => array('code' => '1', 'msg'     => '资产'),
    'CATEGORY_LIABILITIES'     => array('code' => '2', 'msg'     => '负债'),
    'CATEGORY_OWNERS'          => array('code' => '3', 'msg'     => '所有者权益'),
    'CATEGORY_REVENUE'         => array('code' => '4', 'msg'     => '收入'),
    'CATEGORY_EXPENSES'        => array('code' => '5', 'msg'     => '费用'),
    'CATEGORY_INCOME'          => array('code' => '6', 'msg'     => '收益'),
     */
    public function getAccountCategory($general_account_code){
        $account_category = ['1002'  =>'1','1122'  =>'1','2202' =>'2','6001' =>'6','6401' =>'5'];
        return $account_category [$general_account_code];
    }

    protected function getCode($key){
        return Config::get('finance.'.$key.'.code');
    }

    protected function getMsg($key){
        return Config::get('finance.'.$key.'.msg');
    }


}