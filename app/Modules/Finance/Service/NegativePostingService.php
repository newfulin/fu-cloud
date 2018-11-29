<?php
/**
 * 反记账类
 * 
 */
namespace App\Modules\Finance\Service ;

use App\Common\Contracts\Service;
use Illuminate\Support\Facades\Log;
use App\Events\FinanceRegisterEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Entity\CashierEntity;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;

/**
 * 反记账类
 * Class NegativePostingService
 * @package App\Modules\Finance\Service
 */
class NegativePostingService extends Service {

    public $bookingOrder;
    public $accountBalance;

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingOrderRepository $bookingOrder,AcctAccountBalanceRepository $accountBalance){
         $this->bookingOrder = $bookingOrder;
         $this->accountBalance = $accountBalance;
    }

    public function getRules()
    {
        log::debug("BookingService.getRules...");
        return [];
    }
    
    /**
     * 执行
     */
    public function handle($request){
        Log::info("NegativePostingService....");
        $reqCode = $request['reqCode'];
        $batchId = $request['batchId'];
        $negativePostingOrders = $this->cashierNegativePosting($reqCode,$batchId);
        $ret = '0000';
        $cashierEntity = new CashierEntity();
        $cashierEntity->setBatchId($negativePostingOrders[0]['batch_id']);
        $cashierEntity->setReqCode($negativePostingOrders[0]['voucher_code']);//请求码
        Event::fire(new FinanceRegisterEvent($cashierEntity));
        return $ret;
    }


    /**
     * 反记账生成记账流水
     */
    public function cashierNegativePosting($reqCode,$batchId)
    {
        $orders = $this->getNeedBookOrder($reqCode,$batchId);
        //生成记账会计分录
        $negativePostingOrders = $this->creatBookingOrder($orders);
        Log::info(json_encode($negativePostingOrders));
        $this->checkRepeat($negativePostingOrders);
        //记账凭证的试算平衡
        $this->checkBalanceTrialCalculation($negativePostingOrders);
        //请求记账服务模块记账
        foreach ($negativePostingOrders as $key => $entity) {
            Log::info(json_encode($entity));
            $this->bookingOrder->save($entity);
        }
        return $negativePostingOrders;
    }

    /**
     * 查重
     */
    public function checkRepeat($booking_order){
        $external_con_order = $booking_order[0]['external_con_order'];
        $isRepeat = $this->bookingOrder->getCountByExternalConOrder($external_con_order);
        if($isRepeat>0){
            Log::error("checkRepeat....会计分录重复");
            Err('JOURNAL_EXIST');
        }
    }

    /**
     * 生成反记账,记账会计分录
     */
    public function creatBookingOrder($orders)
    {
        //生成批次id
        $batch_id = ID();
        $result = array();           //初始化结果
        Log::info("生成反记账,记账会计分录 批次batch_id:".$batch_id);
        foreach ($orders as $key => $value) {
            //循环模板
            //Log::info("foreach::",json_encode($value));
            $rs = array();
            $rs['id'] = ID();
            //批次号
            $rs['batch_id']             = $batch_id;
            //明细id
            $rs['batch_detail_id']      = $value['batch_detail_id'];
            //账套号
            $rs['account_id']           = $value['account_id'];
            //记账请求码
            $rs['voucher_code']         = str_replace("K","N",$value['voucher_code']);
            // 记账时间
            $rs['booking_time']         = date("Y-m-d H:i:s");
            // 总账科目
            $rs['general_account_code'] = $value['general_account_code'];
            // 账户类型
            $rs['account_type']         = $value['account_type'];
            // 账户对象
            $rs['account_object']       = $value['account_object'];
            // 摘要
            $rs['remark']               = "反记-".$value['remark'];
            // 借贷方向  互换
            $rs['debit_credit_direction'] = $value['debit_credit_direction']==1?2:1;
            //借贷金额 互换
            $rs['debit_amount']  = $value['credit_amount'];
            $rs['credit_amount'] = $value['debit_amount'];

            //取得项目对象处理类
            $rs['process_id']    = $value['process_id'];
            // 明细科目编码
            $rs['detail_account_code'] = $value['detail_account_code'];
           
            //外联流水id
            $rs['external_con_order']     = "N".$value['external_con_order'];
            // 科目类别码general_account_code
 
            $rs['account_category']       = $value['account_category'];

            // 记账人
            $rs['booking_by']             = 'sytem';
            // 审核人
            $rs['approving_by']           = 'sytem';
            // 财务主管
            $rs['financia_manager_by']    = 'sytem';
            // 出纳
            $rs['cash_by']                = 'sytem';
            // 制单人
            $rs['make_by']                = 'sytem';
            // 经办人
            $rs['operator_by']            = 'sytem';
            // 录入方式
            $rs['input_mode']             = 'PMS反记账';
            // 反记账流水
            $rs['reset_booking_order']    = $value['batch_id'];
            // 反记账原因
            $rs['reset_booking_reason']   = '操作错误';
            // 科目余额更新状态,未更新
            $rs['account_balance_status'] = $this->getCode('BOOK_BALANCE_NO_UPDATE');
            // 余额更新时间
            $rs['account_balance_time']   = null ;
            // 记账状态:                        记账，人工制单时有 制单审核等状态
            $rs['account_status']         = $this->getCode('BOOK_ORDER_BOOKING');
            $rs['create_time']            = date("Y-m-d H:i:s");
            $rs['create_by']              = 'sytem';
            $rs['update_time']            = date("Y-m-d H:i:s");
            $rs['update_by']              = 'sytem';
            $debit_amount  = Money()->format($rs['debit_amount']);
            $credit_amount = Money()->format($rs['credit_amount']);
            if($debit_amount == '0.00' AND $credit_amount == '0.00'){
                //计算结果为0时，跳过本次循环
                //continue;
            }

            $result[$key] = $rs;
        }
        
        return $result;
    }

    /**
     * 平衡试算
     */
    public function checkBalanceTrialCalculation($booking_order)
    {
        Log::debug("checkBalanceTrialCalculation....平衡试算");
        //平衡计算
        $totalDebitAmount  = Money()->format('0.00');  //借方合计金额
        $totalCreditAmount = Money()->format('0.00'); //贷方合计金额
        foreach ($booking_order as $key => $value) {
            $totalDebitAmount  = Money()->calc($totalDebitAmount,"+",$value['debit_amount']) ;
            $totalCreditAmount = Money()->calc($totalCreditAmount,"+",$value['credit_amount']) ;
        }
        $totalDebitAmount  = Money()->format($totalDebitAmount);
        $totalCreditAmount = Money()->format($totalCreditAmount);
        Log::info("借方总金额:=".$totalDebitAmount);
        Log::info("贷方总金额:=".$totalCreditAmount);
        if (!Money()->calc($totalCreditAmount,'==',$totalDebitAmount)) {
            Err('DEBIT_CREDIT_ERR');
        }
        Log::info('借方:'.$totalDebitAmount .'=贷方:'.$totalCreditAmount);
    }

   /**
     * 获得需要反记账的明细流水
     */
    public function getNeedBookOrder($reqCode,$batchId)
    {
        $orders = $this->bookingOrder->getNegativePostingNeedBookOrder($reqCode,$batchId);
        if(empty($orders)){
            Err("该批次没有可以反记账的数据:9999");
        }
        //记账凭证的试算平衡
        $this->checkBalanceTrialCalculation($orders);
        return $orders;
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