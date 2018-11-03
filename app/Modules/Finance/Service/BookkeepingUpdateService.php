<?php
/**
 * 记账更新 更新账户余额
 * Class BookkeepingUpdateService
 * @package App\Modules\Finance\Service
 */
namespace App\Modules\Finance\Service ;

use App\Modules\Finance\Finance;
use App\Common\Contracts\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;

/**
 * 记账更新 更新账户余额
 * Class BookkeepingUpdateService
 * @package App\Modules\Finance\Service
 */
class BookkeepingUpdateService extends Service {

    public $bookingOrder;
    public $accountBalance;

    /**
     * 注入Repository
     */
    public function  __construct(AcctBookingOrderRepository $bookingOrder,AcctAccountBalanceRepository $accountBalance){
         $this->bookingOrder = $bookingOrder;
         $this->accountBalance = $accountBalance;
    }

    //收银统一的检查中间件...
    public $middleware = [];

    /**
     * 参数验证
     * @return array
     */
    public function getRules()
    {
        Log::debug("BookkeepingUpdateService.getRules...");
        return [];
    }

    /**
     * 执行
     */
    public function handle($request)
    {
        //记账批次
        $needBookOrder = $request['needBookOrder'];
        Log::info("执行 BookkeepingUpdateService.handle...");
        foreach($needBookOrder as $key=>$order){
            //记账账户
            $account = $this->getAccount($order);
            //期末凭证流水
            $this->checkClosingOrder($account) ; 
            //当前流水发生额
            $occurred_amount = $this->getAmount($order);
            //收入为1,支出为2
            $direction = ($occurred_amount >0) ? '1': '2' ; 
            Log::info('order_occurred_amount:|'.$occurred_amount);
            $balance = Money()->calc($account['balance'] ,'+', $occurred_amount) ;
            if($balance < 0) {
                $this->lockFinance("9999:账户余额计算为负数");
            }
            $balanceUpdate                = array(
                'balance'                => $balance ,
                'opening_balance'        => $account['balance'],
                'occurred_amount'        => abs($occurred_amount),
                'closing_order'          => $order['id'],
                'direction'              => $direction,
                'update_time'            => date('Y-m-d H:i:s')
            );
            $bookUpdate                   = array(
                'account_balance_status' => 1,
                'account_balance_time'   => date('Y-m-d H:i:s'),
                'update_time'            => date('Y-m-d H:i:s')
            );
            $this->accountBalance->update($balanceUpdate,$account['id']);
            $this->bookingOrder->update($bookUpdate,$order['id']);
        }
        return '0000';
    }

    /**
     * 获取需要记账的账户
     */
    public function getAccount($order)
    {
        Log::info("process_id:|".$order['process_id']);
        //取出process_id 对应的账户记录,并查询出最期末账单
        $account = $this->accountBalance->getAccountById(
            $order['account_id'],
            $order['process_id'],
            $order['account_object'],
            $order['account_type']
        );
        if(empty($account)){
            Err("记账账户锁定或不存在|".$order['process_id'].':9999');
        }
        Log::info('account_balance:|'.$account['balance']);
        Log::info('account_opening_balance:|'.$account['opening_balance']);
        //如果账户的期末凭证为空
        Log::info('closing_order:|'.$account['closing_order']);
        if(!$account['closing_order']){
            $amountOccurred = Money()->calc($account['opening_balance'] ,'+', $account['occurred_amount']);
            $amountTotal = Money()->calc($account['balance'] ,'+', $amountOccurred);
            if(!Money()->calc($amountTotal,"==","0.00")){
                $this->lockFinance("没有期末凭证账户应该为初始状态:9999");
            }
        }
        $occurred_amount = 0.00;
        //判断此账户的收入还是支出
        if($account['direction'] == '1'){
            $occurred_amount  = $account['occurred_amount'];
        }else if($account['direction'] == '2') {
            $occurred_amount  = -$account['occurred_amount'];
        }
        //Log::debug("判断用户余额与发生额是否一致::".$account['balance'].'!='.Money()->calc($account['opening_balance'] ,'+', $occurred_amount ));
        if(Money()->format($account['balance']) != Money()->calc($account['opening_balance'] ,'+', $occurred_amount ))
        {
            Log::info($account['balance'].'!='.Money()->calc($account['opening_balance'] ,'+', $occurred_amount ));
            $this->lockFinance("账户测算不平衡:9999");
        }
        return $account;
    }

    /**
     * 检查期末流水
     */
    public function checkClosingOrder($account)
    {
        Log::debug('checkClosingOrder:');
        $amount = '0.00';
        Log::debug($account['closing_order']);
        if($account['closing_order']){
            //期末流水凭证
            $order = $this->bookingOrder->getBookOrderById($account['closing_order'],'1');
            if(empty($order)){
                Err("期末凭证不存在|".$account['closing_order'].':9999');
            }
            //期末流水凭证发生额
            $amount = abs($this->getAmount($order));
        }
        Log::info('closing_order_amount:|'.$amount);      
        if(!Money()->calc($amount,"==",$account['occurred_amount'])){
            $this->lockFinance('发生额不等于凭证期末额:9999');
        }

    }

    /**
     * 获取流水发生额
     */
    public function getAmount($order)
    {
        Log::debug('getAmount:');
        $ret =sprintf("%.2f", 0);
        switch ($order['account_category']) {
            case Config::get("finance.CATEGORY_ASSET.code");//DICode('finance', "CATEGORY_ASSET")://资产
            case Config::get("finance.CATEGORY_EXPENSES.code");//DICode('finance', "CATEGORY_EXPENSES")://费用
                //贷方表示减少
                //余额计算  借-贷
                $ret = Money()->calc($order['debit_amount'] ,'-', $order['credit_amount']);
                break;
            case Config::get("finance.CATEGORY_LIABILITIES.code");//DICode('finance', "CATEGORY_LIABILITIES")://负债
            case Config::get("finance.CATEGORY_INCOME.code");//DICode('finance', "CATEGORY_INCOME")://收益
            case Config::get("finance.CATEGORY_REVENUE.code");//DICode('finance', "CATEGORY_REVENUE")://收入
            case Config::get("finance.CATEGORY_OWNERS.code");//DICode('finance', "CATEGORY_OWNERS")://所有者权益
                //贷-借
                $ret = Money()->calc($order['credit_amount'] ,'-', $order['debit_amount']);
                break;
        }
        Log::debug('getAmount:End.'.$ret);
        return $ret ;
    }

    public function lockFinance($message)
    {
        Log::error($message);   
        $dirFile = __DIR__."/finance.lock";
        $APP_ENV = env('APP_ENV');
        //product
        //development
        if($APP_ENV == 'product'){
            $file = str_replace( 'app/Modules/Finance/Service' , 'storage',$dirFile);
            Log::error($file); 
            file_put_contents($file, '1');
        }
        Err('FINANCE_LOCK');
    }

    //
    public function getConfigMiddleware($method)
    {
        //取得记账码对应的中间件
        $config = 'finance.bookingupdate';
        Log::debug("BookkeepingUpdateService.getConfigMiddleware...".$config);
        return Config::get($config);
    }


}