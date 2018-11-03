<?php
/**
 * php artisan queue:work  --tries=1
 */

namespace App\Modules\Finance\Listeners;

use App\Modules\Finance\Finance;
use Illuminate\Support\Facades\Log;
use App\Events\FinanceRegisterEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegisterFinanceListener implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent $event
     * @return void
     */
    public function handle(FinanceRegisterEvent $event)
    {
        //app('redis')->set('CashierServiceRedis','123123');
        //$con  = app('redis')->connections();
        //echo json_encode(app('redis')->zrange('test',0,time()));
        Log::info('异步处理 记账更新 更新账户余额 ');
        Log::info(json_encode($event->object));
        Log::debug("bookingkupdate 账单更新处理服务接口...财务请求码:".$event->object->reqCode.
        ",批次号:".$event->object->batchId);
        $code = $event->object->reqCode;
        $batchId = $event->object->batchId;
        $ret = Finance::service('BookkeepingUpdateService')
            ->with('reqCode',$code)
            ->with('batchId',$batchId)
            ->runTransaction();
        Log::info($ret);
        echo '0000';
    }

    public function onRegister(FinanceRegisterEvent $event)
    {
        echo '9999';
    }


}