<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Repository\ActivityManageRepo;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckLVMiddleware extends Middleware
{
    public $repo;
    public function __construct(ActivityManageRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.
        Log::debug('------参数检测----'.json_encode($request));
        $control = $this->repo->getUserControl($request['act_id']);
        if(!$control['user_control']){
            return $next($request);
        }
        $checkArr = explode(',', $control['user_control']);
        foreach ($checkArr as $key => $val) {
            if($request['tariff_code'] == $val) {
                return $next($request);
            }
        }
    }
}