<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;

/**
 * 检查账户金豆余额,咖啡豆账户余额
 */
class CheckCoffeeBean extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(AcctAccountBalanceRepository $Repository){
         $this->repository = $Repository;
    }
    
    public function handle($request, Closure $next)
    {
        Log::info("K0210 +=+ 检查账户金豆余额,咖啡豆账户余额 ");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $transAmount = $request['transAmount'];//交易金额,提现金额
        $userinfo = $request['userinfo'];
        $account = $this->repository->getAccountById(
            '1',
            $userinfo['id'],
            '80',
            '40' //咖啡豆
        );
        if(empty($account)){
            Err("记账账户锁定或不存在|".$userinfo['id'].':9901');
        }
        $balance = $account['balance'];
        Log::info('account_balance:|'.$account['balance']);
        if(Money()->calc($transAmount,"-",$balance)>0){
            Err("用户消费金额大于账户金豆余额:9900");
        }
        return $request;
    }
}