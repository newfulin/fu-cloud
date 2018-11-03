<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:41
 */

namespace App\Modules\Callback\Events;

use log;

class DemoServiceBeforeEvent {


    public $request ;

    public function __construct($request)
    {
        log::debug("DemoServiceBeforeEvent.handle...".json_encode($request));
        $this->request = $request;
    }


}