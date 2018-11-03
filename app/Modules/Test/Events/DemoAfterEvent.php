<?php
/**
 * php artisan queue:work --daemon --quiet --queue=default --delay=3 --sleep=3 --tries=1
 * php artisan queue:work  --tries=1
 */
namespace App\Modules\Test\Events;

use App\Events\Event;
use Illuminate\Support\Facades\Log;


class DemoAfterEvent extends Event {


//    public $object ;
//
//    public function __construct($request)
//    {
//        Log::debug("...DemoAfertEvent.这就是事后事件...");
//        $this->object = $request;
//
//    }


}