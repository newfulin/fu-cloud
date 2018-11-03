<?php

namespace App\Modules\Transaction\Middleware\Trans;

use App\Common\Contracts\Middleware;
use Closure;

class GeneratingOrderMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.
        $request['detailId'] = ID();
        $request['summaryId'] = ID();
        return $next($request);
    }
}
