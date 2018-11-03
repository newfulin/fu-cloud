<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/7
 * Time: 12:01
 */

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(QueryExecuted $event)
    {
        $sql = str_replace("?", "'%s'", $event->sql);

        $log = vsprintf($sql, $event->bindings);

        Log::info($log);
    }

}