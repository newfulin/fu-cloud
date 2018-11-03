<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/28
 * Time: 15:11
 */
namespace App\Modules\Finance\Middleware;

use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Config;

class CheckAccountMiddle extends Middleware{




    public function handle($request, Closure $next)
    {
        //账户对象 用户
        $request['mercObj'] = Config::get("finance.ACCOUNT_OBJECT_USER.code");//DICode('finance','ACCOUNT_OBJECT_USER');//80

        //账户类型 资产，信用，冻结，红包，垫资，积分
        $request['asset']  = Config::get("finance.ACCOUNT_TYPE_ASSET.code");//DICode('finance','ACCOUNT_TYPE_ASSET');
        $request['credit'] = Config::get("finance.ACCOUNT_TYPE_CREDIT.code");//DICode('finance','ACCOUNT_TYPE_CREDIT');
        $request['freeze'] = Config::get("finance.ACCOUNT_TYPE_FREEZE.code");//DICode('finance','ACCOUNT_TYPE_FREEZE');
        $request['lend']   = Config::get("finance.ACCOUNT_TYPE_LEND.code");//DICode('finance','ACCOUNT_TYPE_LEND');
        $request['points'] = Config::get("finance.ACCOUNT_TYPE_POINTS.code");//DICode('finance','ACCOUNT_TYPE_POINTS');
        $request['reward'] = Config::get("finance.ACCOUNT_TYPE_REWARD.code");//DICode('finance','ACCOUNT_TYPE_REWARD');
        return $next($request);
    }
}