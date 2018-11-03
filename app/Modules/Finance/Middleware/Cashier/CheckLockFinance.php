<?php
namespace App\Modules\Finance\Middleware\Cashier;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;

/**
 * 财务系统检查
 */
class CheckLockFinance extends Middleware{


    public function handle($request, Closure $next)
    {
        Log::info("财务系统检查");
        //$this->lockFinance("锁死财务系统");
        $this->checkFinance();
        return $next($request);
    }

    /**
     * 财务系统检查
     */
    protected function checkFinance(){
        $dirFile = __DIR__."/finance.lock";
        $file = str_replace( 'app/Modules/Finance/Middleware/Cashier' , 'storage',$dirFile);
        $APP_ENV = env('APP_ENV');
        if(is_file($file) && filesize($file)>0){
            //product
            //development
            if($APP_ENV=="product"){
                Err("FINANCE_LOCK");
            }
        }
    }
    /**
     * 锁死财务系统
     */
    public function lockFinance($message)
    {
        Log::error($message);   
        $dirFile = __DIR__."/finance.lock";
        $APP_ENV = env('APP_ENV');
        //product
        //development
        if($APP_ENV == 'product'){
            $file = str_replace( 'app/Modules/Finance/Middleware/Cashier' , 'storage',$dirFile);
            file_put_contents($file, '1');
        }
        Err('FINANCE_LOCK');
    }

}