<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\GoodsOrderRepository;

/**
 * 产品成本检测
 */
class CheckProductCostAmount extends Middleware{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(GoodsOrderRepository $Repository){
         $this->repository = $Repository;
    }
    
    public function handle($request, Closure $next)
    {
        Log::info("产品成本检测");
        $request = $this->checkParams($request);
        return $next($request);
    }

    protected function checkParams($request)
    {
        $order = $request['order'];
        $relationId = $order['relation_id'];    //收银关联订单ID   1150788055959870976
        $goodOrder = $this->repository->getGoodOrder($relationId);
        if(!$goodOrder){
            Err("订单明细信息不存在:9801");
        }
        $goodsId = $goodOrder['goods_id'];
        $number = $goodOrder['number'];
        $goods= $this->repository->getGoods($goodsId);
        if(!$goods){
            Err("产品信息不存在:9802");
        }
        $product_cost_amount = Money()->calc($number,"*",$goods['cost']);
        $request['goodOrder'] = $goodOrder;
        $request['product_cost_amount'] = $product_cost_amount;
        Log::info("产品成本".$product_cost_amount);
        return $request;
    }
}