<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 10:06
 */

namespace App\Modules\Test\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\PurchaseOrderRepo;
use App\Modules\Test\Events\DemoAfterEvent;
use App\Modules\Test\Listeners\RegisterTestListener;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\Log;

class TestService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $afterEvent = [
        DemoAfterEvent::class => []
    ];



    public function updateTest(TranTransOrderRepo $tran,PurchaseOrderRepo $repo,$request){
        $transInfo = $tran->getPayId('1122834262530367744');
//        $transInfo['pay_id'] = '1122834262530367744';

        $arr = [
            'serv_fee' => '01',
            'order_status' => '02'
        ];
        return $repo->update($transInfo['pay_id'],$arr);
    }

    public function testAfter($request){
        Log::info('testAfter');
        return $request;
    }
}