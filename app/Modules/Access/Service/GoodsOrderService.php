<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/8
 * Time: 14:28
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\GoodsInfoRepo;
use App\Modules\Access\Repository\GoodsOrderRepo;
use App\Modules\Access\Repository\WxUserInfoRepo;
use App\Modules\Finance\Repository\AcctAccountBalanceRepository;
use App\Modules\Transaction\Trans;
use Illuminate\Support\Facades\Log;

class GoodsOrderService extends Service
{
    public $order;
    public $user;
    public $wx;
    public $balance;
    public function __construct(GoodsOrderRepo $order,CommUserRepo $user,WxUserInfoRepo $wx, AcctAccountBalanceRepository $balance,GoodsInfoRepo $info)
    {
        $this->order = $order;
        $this->user = $user;
        $this->wx = $wx;
        $this->balance = $balance;
        $this->info = $info;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    // 获取订单详情
    public function getOrderDetail($request)
    {
        $id = $request['id'];
        $re = $this->order->getOrderDetail($id);
        $re['stateName'] = config('common.goods_order.'.$re['state']);
        return $re;
    }
    //生成商品订单
    public function generateGoodsOrder(GoodsInfoRepo $repo,$request){
        //商品信息
        $goodsInfo = $repo->getGoodsInfo($request['goods_id']);

        //总分润
        $promote_profit = Money()->mul($request['number'] , $goodsInfo['promote_profit']);
        //总价格
        $total_price = Money()->mul($request['number'] , $goodsInfo['price']);

        // goodsClass = 20 分润为0
        if ($request['goods_class'] == '20') {
            $promote_profit = '0';
        }

        $data = [
            'id' => ID(),
            'goods_id' => $request['goods_id'],
            'state' => '10',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => $request['user_id'],
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => $request['user_id'],
            'unit_price' => $goodsInfo['price'],
            'total_price' => $total_price,
            'promote_profit' => $promote_profit,
            'number' => $request['number'],
            'address' => $request['address'],
            'consignee_name' => $request['consignee_name'],
            'consignee_mobile' => $request['consignee_mobile'],
            'user_id' => $request['user_id'],
            'goods_type' => $request['goods_type'],
            'goods_class' => $request['goods_class'],
            'from_way' => $request['from_way']
        ];

        $ret = $this->order->insert($data);
        if($ret) return $data['id'];
        else Err('订单生成失败');
    }

    //获取商品订单列表
    public function getGoodsOrder($request){
        $ret = $this->order->getGoodsOrderList($request);
        foreach ($ret as $key => $val){
            if($val->img) $val->img = R($val->img,false);
            $val->state_name = config('common.goods_order.'.$val->state);
        }
        return $ret;
    }

    //商品订单支付
    public function payGoodsOrder($request){
        //获取用户详情
        $userInfo = $this->user->getUser($request['user_id']);
        $wxInfo = $this->wx->getWxInfo($request['user_id']);

        if(!$userInfo) Err('请重新登录');
        //获取订单详情
        $orderInfo = $this->order->getGoodsInfoByOrderId($request);

        if(!$orderInfo || $orderInfo->state == '20') Err('订单已支付,请忽重复提交!');

        $total_price = Money()->mul($orderInfo->number , $orderInfo->price);

        $ret = [];

        // 普通商品订单
        if ($orderInfo->goods_class == '10') {
            $ret = Trans::service('ChannelTrans')
                ->with('business_code','A0600')
                ->with('trans_amt',$total_price)
                ->with('order_id',$orderInfo->id)
                ->with('tariff_code',$userInfo['user_tariff_code'])
                ->with('user_id',$request['user_id'])
                ->with('open_id',$wxInfo['openid'])
                ->with('order_class','10')
                ->with('time',time())
                ->run('payGoodsOrder');
            $ret['user_id'] = $request['user_id'];
        }

        // 积分商品订单 验证用户资产是否足够   $total_price
        if ($orderInfo->goods_class == '20') {
            $balance = $this->balance->getBalance($request['user_id'],'50');
            if ((int)$balance < (int)$total_price)
            {
                Err('对不起您的积分余额不足');
            }

            $ret = Trans::service('ChannelTrans')
                ->with('business_code','A0620')
                ->with('trans_amt',$total_price)
                ->with('order_id',$orderInfo->id)
                ->with('tariff_code',$userInfo['user_tariff_code'])
                ->with('user_id',$request['user_id'])
                ->with('order_class','20')
                ->with('time',time())
                ->run('payIntegral');
            // 记账成功 扣款成功 更改订单状态
            if ($ret['finance'] != '0000') {
                Err('支付失败');
            }
            return '0000';

        }

        return $ret;
    }

    //确认收货
    public function confirmGoods($request){
        //支付成功可确认收货,修改为交易成功
        $orderInfo = $this->order->getOrderInfoByState($request['order_id'],'25');

        //修改订单状态
        $state = $this->editOrderState($orderInfo);
        if(!$state) Err('网络繁忙,确认收货失败!');
        return $state;
        //获取用户详情
//        $userInfo = $this->user->getUser($request['user_id']);

//        return Trans::service('ChannelTrans')
//            ->with('business_code','A0610')
//            ->with('trans_amt',$orderInfo['promote_profit'])
//            ->with('goods_order_id',$request['order_id'])
//            ->with('tariff_code',$userInfo['user_tariff_code'])
//            ->with('user_id',$request['user_id'])
//            ->with('time',time())
//            ->run('orderShareProfit');
    }

    //确认收货,修改订单状态
    public function editOrderState($orderInfo){
        if($orderInfo){
            return $this->order->update($orderInfo['id'],['state' => '60']);
        }
        Err('当前订单不可确认收货');
    }

    //取消订单
    public function cancelOrder($request){
        //已支付订单,不可删除
        Err('开发中...');
//        $orderInfo = $this->order->getOrderInfoByState($request['order_id'],'20');
    }

    //订单删除
    public function delOrder($request){
        //已支付订单,不可删除
        $orderInfo = $this->order->getOrderInfoByState($request['order_id'],'20');
        if($orderInfo){
            Err('当前订单不可删除');
        }

        return $this->order->update($request['order_id'],['state' => '50']);
    }
    public function generateVirtualGoodsOrder(GoodsInfoRepo $repo,$request){
        //商品信息
        $goodsInfo = $repo->getGoodsInfo($request['goods_id']);


        //总分润
        $promote_profit = Money()->mul($request['number'] , $goodsInfo['promote_profit']);
        //总价格
        $total_price = Money()->mul($request['number'] , $goodsInfo['price']);
        $time = date('Y-m-d H:i:s',time() - mt_rand(1000,2000));
        $data = [
            'id' => ID(),
            'goods_id' => $request['goods_id'],
            'state' => '60',
            'create_time' => $time,
            'create_by' => $request['user_id'],
            'update_time' => $time,
            'update_by' => $request['user_id'],
            'unit_price' => $goodsInfo['price'],
            'total_price' => $total_price,
            'promote_profit' => $promote_profit,
            'number' => $request['number'],
            'user_id' => $request['user_id'],
            'order_type' => '20',
            'goods_type' => '10'
        ];
        $ret = $this->order->insert($data);
        if($ret) return $data['id'];
        else Err('订单生成失败');
    }

    public function updateSales($request)
    {
        $ret = $this->info->setIncrementing($request['goods_id']);
        return $ret;
    }
}