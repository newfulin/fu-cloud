<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 10:39
 */
namespace App\Modules\Finance\Service;


use App\Common\Contracts\Service;
use App\Events\FinanceRegisterEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Entity\CashierEntity;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;
use Illuminate\Support\Facades\Log;

/**
 * 插入记账流水
 */
class BookingService extends Service {

    public $middleware = [];

    public $repository;

    public $afterEvent = [
        FinanceRegisterEvent::class
    ];

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingOrderRepository $Repository){
         $this->repository = $Repository;
    }

    public function getRules()
    {
        Log::debug("BookingService.getRules...");
        return [];
    }

    public function handle($request){
        Log::info("BookingService....!!!!!!!!!!!!!!!");
        $booking_order = $request['book']['booking_order'];
        //Log::info(json_encode($booking_order));
        $this->checkRepeat($request);
        $this->checkBalanceTrialCalculation($request);
        $this->requestData = $request;
        //return $request;//测试返回数据
        foreach ($booking_order as $key => $entity) {
            //Log::info(json_encode($entity));
            $this->repository->save($entity);
        }
        return $request;
    }

     /**
     * 重新定义发送事件
     * @param $event
     * @param $request
     */
    public function eventFire($event,$request)
    {
        Log::info("...FinanceEvent.执行财务队列,事后请求事件...");
        //Log::debug(json_encode($request));
        $cashierEntity = new CashierEntity();
        $cashierEntity->setBatchId($request['book']['batch_id']);
        $cashierEntity->setReqCode($request['policy']);//策略后的请求码
        Event::fire(new FinanceRegisterEvent($cashierEntity));
    }

    /**
     * 平衡试算
     */
    public function checkBalanceTrialCalculation($request)
    {
        Log::debug("checkBalanceTrialCalculation....平衡试算");
        //平衡计算
        $totalDebitAmount  = Money()->format('0.00');  //借方合计金额
        $totalCreditAmount = Money()->format('0.00'); //贷方合计金额
        $booking_order = $request['book']['booking_order'];
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
     * 查重
     */
    public function checkRepeat($request){
        $booking_order = $request['book']['booking_order'];
        $external_con_order = $booking_order['1']['external_con_order'];
        $isRepeat = $this->repository->getCountByExternalConOrder($external_con_order);
        if($isRepeat>0){
            Log::error("checkRepeat....会计分录重复");
            Err('JOURNAL_EXIST');
        }
    }
    
    protected function getMsg($key){
        return Config::get('finance.'.$key.'.msg');
    }
}