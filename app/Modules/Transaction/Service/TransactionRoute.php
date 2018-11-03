<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 18:27
 */
namespace App\Modules\Transaction\Service;

use App\Common\Contracts\Service;
use App\Modules\Transaction\Events\BeforeRouteEvent;

class TransactionRoute extends Service{
    /**
     * @return mixed
     */

    public $beforeEvent = [
        BeforeRouteEvent::class
    ];
    public $afterEvent = [

    ];


    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function handle($request)
    {
        return "weixin";
    }

}