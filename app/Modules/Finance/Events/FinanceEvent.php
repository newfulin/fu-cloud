<?php
/**
 * php artisan queue:work --daemon --quiet --queue=default --delay=3 --sleep=3 --tries=1
 * php artisan queue:work  --tries=1
 */
namespace App\Modules\Finance\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class FinanceEvent extends Event {


    public $request ;

    public function __construct($request)
    {
        Log::debug("...FinanceEvent.这就是请求队列的财务队列请求事件...");
    }

}