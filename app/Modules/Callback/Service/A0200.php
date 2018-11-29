<?php

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\GoodsPayOrderRepo;
use App\Modules\Transaction\Repository\WxPayOrderRepo;
use Illuminate\Support\Facades\Log;

class A0200 extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }
    protected $wx;
    public function __construct(WxPayOrderRepo $wx)
    {
        $this->wx = $wx;
    }
    public function handle($request){
        $data = $request['data'];
        Log::info('订单支付 更新流水 成功 | ' . $data['order_id']);
        return $data;
    }
    //更新流水
    public function update($request){
        Log::info(' 积分充值 | '.json_encode($request));
        $params = [
            'update_time' => date('Y-m-d H:i:s'),
            'time_expire' => $request['params']['time_expire'],
            'state' => $request['params']['state'],
            'status' => $request['params']['status'],
            'acct_res_code' => $request['params']['acct_res_code'],
        ];
        return $this->wx->updateWxOrder($request['detailId'],$params);
    }
    public function getDetailOrder($request){
        return $this->wx->getDetailOrder($request['detailId']);
    }
}