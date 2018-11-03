<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:12
 */

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\GoodsInfoRepo;
use App\Modules\Access\Repository\GoodsOrderRepo;
use App\Modules\Access\Repository\GoodsPayOrderRepo;
use Illuminate\Support\Facades\Log;

class A0600 extends Service
{

    public $payorder;
    public $order;
    public $user;
    public $info;
    public function __construct(GoodsPayOrderRepo $payorder,GoodsOrderRepo $order,CommUserRepo $user,GoodsInfoRepo $info)
    {
        $this->payorder = $payorder;
        $this->order = $order;
        $this->user = $user;
        $this->info = $info;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function handle($request){
        $data = $request['data'];
        Log::info('订单支付 更新流水 成功 | ' . $data['order_id']);
        return $data;
    }

    //订单支付 更新流水
    public function update($request){
        Log::info(' 订单支付 更新流水 | '.$request['detailId']);
        //更新订单支付流水
        $this->payorder->update($request['detailId'],$request['params']);
        //更新订单支付
        $orderInfo = $this->payorder->getDetailOrder($request['detailId']);

        $param = [
            'state' => '20'
        ];
        $this->order->update($orderInfo['order_id'],$param);

        //更新商品销量
        $this->updateGoodsSales($orderInfo);
        //更新用户等级
        $this->updateUserLevel($orderInfo);
    }

    //更新商品销量
    public function updateGoodsSales($orderInfo){
        //更新商品销量  sales
        $info = $this->order->getOrderInfo($orderInfo);

        return $this->info->setIncrementing($info['goods_id']);
    }

    //更新用户等级
    public function updateUserLevel($orderInfo){
        //用户等级为普通用户,升级为会员用户
        //获取用户信息
        $userInfo = $this->user->getUser($orderInfo['user_id']);

        if($userInfo['user_tariff_code'] < config('const_user.MEMBER_USER.code')){
            //更新用户等级
            $data = [
                'user_tariff_code' => config('const_user.MEMBER_USER.code'),
                'level_name' => config('const_user.MEMBER_USER.code'),
            ];
            $this->user->updateUser($orderInfo['user_id'],$data);
        }
    }

    public function getDetailOrder($request){
        return $this->payorder->getDetailOrder($request['detailId']);
    }
}