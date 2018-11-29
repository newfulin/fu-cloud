<?php

namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\WxPayOrder;
use Illuminate\Support\Facades\Log;

class WxPayOrderRepo extends Repository
{
    public function __construct(WxPayOrder $model)
    {
        $this->model = $model;
    }

    public function createOrder($request)
    {
        return $this->model->insert($request);
        $result = $request['result'];
        $time = $result['timestamp'];
        $this->model->id = $result['out_trade_no'];
        $this->model->sign = $result['sign'];
        $this->model->body = $result['body'];
        $this->model->total_fee = $result['total_fee'];
        $this->model->spbill_create_ip = $result['spbill_create_ip'];
        $this->model->time_start = date("Y-m-d H:i:s", $time);
        $this->model->time_expire = date("Y-m-d H:i:s", $time + 600);
        $this->model->create_by = 'system';
        $this->model->update_time = date("Y-m-d H:i:s", $time);
        $this->model->update_by = 'system';
        $this->model->prepayid = $result['prepayid'];
        $this->model->state = '1';
        $this->model->user_id = $request['user_id'];
        $ret = $this->model->save();

        return $ret;
    }
    public function updateWxOrder($id,$params)
    {
        return $this->model->where('id',$id)->update($params);
    }
    // 获取活动订单数
    public function getActCount($body)
    {
        $ret = optional($this->model
            ->where('body',$body)
            ->where('state','2')
            ->get())
            ->toArray();
        return count($ret);
    }
    //查询流水详情
    public function getDetailOrder($id)
    {
        return optional($this->model->select('id','user_id','trans_amt','business_code')
            ->where('id',$id)
            ->first())
            ->toArray();
    }
}