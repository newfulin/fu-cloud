<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/27
 * Time: 16:54
 */
namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Finance\Finance;
use Closure;
use Illuminate\Support\Facades\Log;

class CreateAccount extends Middleware{
    public function handle($request, Closure $next)
    {
        Log::info('创建账户 ->' .$request['user_id']);


        Finance::service('CreateAccountService')
            ->with('acct_id',1)
            ->with('user_id',$request['user_id'])
            ->run('createAccount');

        return $next($request);
    }
}