<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Closure;

class InsuranceMiddleware extends Middleware
{
    public $repo;
    public function __construct(TranTransOrderRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.
        $check = $this->repo->checkOuterOrderId($request['outer_order_id']);
        if ($check) {
            Err('重复的承保单号','7777');
        }
        $request['detailParams']['outer_order_id'] = $request['outer_order_id'];
        return $next($request);
    }
}