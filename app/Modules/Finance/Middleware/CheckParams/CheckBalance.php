<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;

/**
 * 检查账户余额 提现金额是否足够提现
 */
class CheckBalance extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(AcctAccountBalanceRepository $Repository){
         $this->repository = $Repository;
    }
    
    public function handle($request, Closure $next)
    {
        Log::info("K0700 +=+ 检查账户余额 提现金额是否足够提现");
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
            '10'
        );
        if(empty($account)){
            Err("记账账户锁定或不存在|".$userinfo['id'].':9907');
        }
        $balance = $account['balance'];
        Log::info('account_balance:|'.$account['balance']);
        if(Money()->calc($transAmount,"-",$balance)>0){
            Err("用户提现金额大于账户余额:9911");
        }
        return $request;
    }
}