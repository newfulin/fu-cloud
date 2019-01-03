<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/14
 * Time: 16:13
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\GoodsInfoRepo;
use Closure;

class CheckStoreStatusMiddle extends Middleware {
    public $repo;

    public function __construct(GoodsInfoRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next){
        $status = $this->repo->getStoreStatusByGoodsID($request);
        if ($status->status != 10){
            $request['error'] = "店铺已关闭或正在审核";
        }
        return $next($request);
    }

}