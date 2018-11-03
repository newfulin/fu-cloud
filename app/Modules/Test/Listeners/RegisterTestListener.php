<?php
/**
 * php artisan queue:work  --tries=1
 */

namespace App\Modules\Test\Listeners;


use App\Modules\Test\Events\DemoAfterEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterTestListener implements ShouldQueue
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
    public function handle(DemoAfterEvent $event)
    {

        Log::info('ffffffffffffffffffffffffff');
        return 'gggggg';
        //app('redis')->set('CashierServiceRedis','123123');
        //$con  = app('redis')->connections();
        //echo json_encode(app('redis')->zrange('test',0,time()));
//        Log::info('测试测试!');
//        Log::info(json_encode($event->object));
//        Log::debug("bookingkupdate 账单更新处理服务接口...财务请求码:".$event->object->reqCode.
//        ",批次号:".$event->object->batchId);
//        $code = $event->object->reqCode;
//        $batchId = $event->object->batchId;
        Log::error("这里是lumen ShouldQueue, 异步接收!! ");
        Log::info(json_encode($event->object));
        echo "batchId::".$event->object->batchId;
        echo "reqCode::".$event->object->reqCode;
    }

    public function onRegister(DemoAfterEvent $event)
    {
        echo '9999';
    }


}