<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use App\Common\Models\TranOrder;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Repository\RedPacketRepository;

/**
 * 检查红包情况
 */
class CheckRedPacket extends Middleware
{

    public $repository;

    /**
     * 注入Repository
     */
    public function __construct(RedPacketRepository $Repository)
    {
        $this->repository = $Repository;
    }

    public function handle($request, Closure $next)
    {
        Log::info("检查红包情况");
        $request = $this->checkParams($request);
        return $next($request);
    }
    /**
     * 检测用户资费,同事检测到账金额跟算法是否一致
     */
    protected function checkParams($request)
    {
        Log::info("CheckRedPacket.checkParams");
        return $request;
    }

}