<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 09:51
 */

namespace App\Modules\Finance ;


use App\Common\Contracts\Module;

class FinanceModule extends Module {

    public function getListen()
    {
        // TODO: Implement getListen() method.
        return [
            'App\Events\FinanceRegisterEvent' => [
                'App\Modules\Finance\Listeners\RegisterFinanceListener@handle',
            ],
        ];
    }

    public function getSubscribe()
    {
        // TODO: Implement getSubscribe() method.
    }


}