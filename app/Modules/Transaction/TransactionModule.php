<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 17:04
 */

namespace App\Modules\Transaction;

use App\Common\Contracts\Module;
use App\Modules\Transaction\Events\CallBackAfterEvent;
use App\Modules\Transaction\Events\RequestFinanceEvent;
use App\Modules\Transaction\Listeners\CallBackListener;
use App\Modules\Transaction\Listeners\TransactionChannelListener;

class TransactionModule extends Module {



    public function getListen()
    {
        return [
//             请求财务记账
            RequestFinanceEvent::class => [
                TransactionChannelListener::class
            ],
            CallBackAfterEvent::class => [
                CallBackListener::class
            ],


            'App\Modules\Transaction\Events\BeforeTransaction' => [
                'App\Modules\Transaction\Listeners\TransRiskListener@onBefore'
            ],
            'App\Modules\Transaction\Events\AfterTransaction' => [
                'App\Modules\Transaction\Listeners\TransRiskListener@onAfter'
            ],

            'App\Modules\Transaction\Events\BeforeRouteEvent' => [
                'App\Modules\Transaction\Listeners\BeforeRouteListener'
            ],

        ];
    }

    public function getSubscribe()
    {
        return [];
    }


}