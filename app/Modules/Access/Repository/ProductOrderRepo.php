<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 10:09
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CafeCoffeeHall;
use App\Common\Models\ProductOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductOrderRepo extends Repository
{
    public function __construct(ProductOrder $model)
    {
        $this->model = $model;
    }

    public function generateOrder($request)
    {
        Log::info('---------生成订单-----------'.$request['product_id']);
        $id = ID();
        $create = date('Y-m-d H:i:s');
        $params = [
            'id' => $id,
            'product_id' => $request['product_id'],
            'cafe_id' => $request['cafe_id'],
            'status' => $request['status'],
            'create_time' => $create,
            'create_by' => 'system',
            'update_by' => 'system',
            'update_time' => $create,
        ];
        $ret = $this->model->insert($params);
        if($ret){
            return $id;
        }

    }

    public function getOrderInfo($request)
    {
        $ret = optional(DB::table('product_order as t0')
            ->select('t0.id','t0.product_id','t0.cafe_id','t1.rmb_price','t1.bean_price','t1.product_pic','t1.desc','t1.eng_name','t1.name')
            ->leftJoin('price_product as t1',function ($join){
                $join->on('t0.product_id', '=', 't1.id');
            })
            ->where('t0.id',$request['order_id'])
            ->get())
            ->toArray();
        if($ret){
            if($ret[0]->product_pic){
                $ret[0]->product_pic = R($ret[0]->product_pic,false);
            }

        }
        return $ret;
    }

    public function updateFd($id,$fd)
    {
        $ret = $this->model->where('id',$id)
            ->update([
                'fd' => $fd,
            ]);

        return $ret;
    }

    public function getFd($order_id)
    {
        $ret =$this->model->select('fd')
            ->where('id',$order_id)
            ->first();
        return $ret;
    }

    public function getCafeId($orderId)
    {
        $ret = optional($this->model->select('cafe_id')
            ->where('id',$orderId)
            ->first())
            ->toArray();
        return $ret;
    }

    public function update($id,$data)
    {
        $ret = $this->model->where('id',$id)
            ->update($data);
        return $ret;
    }
}