<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/8
 * Time: 14:28
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\GoodsOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoodsOrderRepo extends Repository {
	public function __construct(GoodsOrder $model) {
		$this->model = $model;
	}

	//获取商品订单列表
	public function getGoodsOrderList($request) {
		$ret = DB::table('goods_order as t0')
			->select('t0.id', 't0.goods_id', 't0.number', 't0.state', 't1.name', 't1.introduce', 't1.integral', 't1.price', 't1.goods_type', 't1.from_way', 't1.url', 't1.img_list as img', 't1.goods_class')
			->leftJoin('goods_info as t1', function ($join) {
				$join->on('t0.goods_id', '=', 't1.id');
			})
			->where('t0.user_id', $request['user_id']);
		if ($request['state']) {
			$ret = optional($ret->where('t0.state', $request['state'])
					->paginate($request['pageSize']))
				->toArray();
		} else {
			$ret = optional($ret->where('t0.state', '!=', '50')
					->paginate($request['pageSize']))
				->toArray();
		}

		return $ret['data'];
	}

	//根据订单ID,获取商品信息
	public function getGoodsInfoByOrderId($request) {
		return DB::table('goods_order as t0')
			->select('t0.id', 't0.goods_id', 't0.number', 't0.state', 't0.goods_class', 't0.total_price', 't1.name', 't1.introduce', 't1.price', 't1.goods_type', 't1.from_way', 't1.url', 't1.img')
			->leftJoin('goods_info as t1', function ($join) {
				$join->on('t0.goods_id', '=', 't1.id');
			})
			->where('t0.order_id', $request['order_id'])
			->first();
	}

	//根据订单状态获取订单信息
	public function getOrderInfoByState($order_id, $state) {
		return optional($this->model
				->select('id', 'goods_id', 'unit_price', 'total_price', 'promote_profit', 'number', 'address', 'consignee_name', 'consignee_mobile', 'user_id', 'state', 'goods_type')
				->where([
					'id' => $order_id,
					'state' => $state,
				])
				->first())
			->toArray();
	}

	//获取订单信息
	public function getOrderInfo($request) {
		return optional($this->model
				->select('id', 'goods_id', 'unit_price', 'total_price', 'promote_profit', 'number', 'address', 'consignee_name', 'consignee_mobile', 'user_id', 'state', 'goods_type')
				->where('id', $request['order_id'])
				->first())
			->toArray();
	}
	// 获取订单详情
	public function getOrderDetail($order_id) {
		return optional($this->model
				->select('order_id as id', 'create_time', 'state', 'total_price', 'goods_id', 'number', 'goods_class')
				->where('order_id', $order_id)
				->get())
			->toArray();
	}

	//根据订单ID获取收货地址
	public function getAddressByOrderId($order_id) {
		$ret = optional($this->model
				->where('order_id', $order_id)
				->with('goodsinfo.getAddressInfo')
				->select()
				->first())
			->toArray();
		return $ret;
	}

	public function update($id, $attributes) {
		return $this->model->where('id', $id)->update($attributes);
	}
}