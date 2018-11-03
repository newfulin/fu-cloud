<?php
/**
 * php artisan queue:work --daemon --quiet --queue=default --delay=3 --sleep=3 --tries=1
 * php artisan queue:work  --tries=1
 */
namespace App\Modules\Finance\Events;

use log;
use Illuminate\Support\Facades\Event;

class DemoServiceBeforeEvent extends Event {


    public $request ;

    public function __construct($request)
    {
        log::debug("...DemoServiceBeforeEvent.这就是事前事件...");
        $M = Money();
        $fen = $M->getYuan2Fen(100);
        log::debug("fen::".$fen);
        $this->request = $request;
        
    }


}