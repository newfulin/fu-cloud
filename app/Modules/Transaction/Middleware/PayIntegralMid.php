<?php
//微信下单
namespace App\Modules\Transaction\Middleware;


use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Access\Repository\GoodsInfoRepo;
use App\Modules\Access\Repository\GoodsOrderRepo;
use App\Modules\Access\Repository\GoodsPayOrderRepo;


class PayIntegralMid extends Middleware
{
    protected $cash;
    protected $payOrder;
    protected $order;
    protected $goods;


    public function __construct(CashOrderRepository $cash,GoodsPayOrderRepo $payOrder,GoodsOrderRepo $order,GoodsInfoRepo $goods)
    {
        $this->cash = $cash;
        $this->payOrder = $payOrder;
        $this->order = $order;
        $this->goods = $goods;
    }
    public function handle($request, Closure $next)
    {
        Log::info('---------PayIntegralMid---------');
        $state = 3;
        $param = [
            'state' => '30'
        ];
        // 成功
        if ($request['finance'] == '0000') {
            $state = 2;
            $param['state'] = '20';
            // 商品销量
            $this->updateGoodsSales($request['WeChatParams']);
        }
        // 更改
        $summaryParams = array(
            'update_time' => date('Y-m-d H:i:s'),
            'status' => $state,
            'acct_res_code' => $request['finance']
        );
        // 收银流水状态
        $this->cash->update($request['summaryId'], $summaryParams);
        // 订单流水状态
        $this->payOrder->update($request['detailId'],$summaryParams);
        // 订单状态
        $this->order->update($request['order_id'],$param);

        return $next($request);
    }
    //更新商品销量
    public function updateGoodsSales($orderInfo){
        //更新商品销量  sales
        $info = $this->order->getOrderInfo($orderInfo);

        return $this->goods->setIncrementing($info['goods_id']);
    }

}