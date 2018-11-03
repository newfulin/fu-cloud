<?php
/**
 * Created by VSCode.
 * User: satsun
 * Date: 2018/2/9
 * Time: 17:35
 */

namespace App\Modules\Finance\Bean\AccountBean ;

use log;

class TransAmount {

    public function handle($request)
    {
        log::debug("TransAmount.handle...".json_encode($request));
        return '1';
    }

}