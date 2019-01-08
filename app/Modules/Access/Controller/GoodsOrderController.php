<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/8
 * Time: 14:26
 */

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoodsOrderController extends Controller {
	public function getRules() {
		return [
			'generateGoodsOrder' => [
				'goods_id' => 'required|desc:商品ID',
				'number' => 'required|desc:商品数量',
				'address' => 'required|desc:收货地址',
				'consignee_name' => 'required|desc:收货人姓名',
				'consignee_mobile' => 'required|desc:收货人电话',
				'goods_type' => 'required|desc:10 食品生鲜 20 服装配饰 30 文体保健 40 家居日化 50 母婴专区 60 特色自营',
				'goods_class' => '|desc:10 普通商品 20 积分商品 30 团购商品 40 秒杀商品',
				'from_way' => '|desc:10 自营 20 拼多多',
			],
			'batchGenerateGoodsOrder' => [
				'shop_id' => 'required|desc:购物车ID(1,2,3)',
				'address_id' => 'required|desc:收获地址ID',
			],
			'getGoodsOrder' => [
				'state' => 'desc:订单状态 默认为空获取全部 10待支付 20已支付 25 待收货 30交易成功  35交易失败  40 取消订单',
				'pageSize' => 'required',
			],
			'payGoodsOrder' => [
				'order_id' => 'required|desc:订单ID',
			],
			'batchPayGoodsOrder' => [
				'order_id' => 'required|desc:订单ID',
			],
			'confirmGoods' => [
				'order_id' => 'required|desc:订单ID',
			],
			'delOrder' => [
				'order_id' => 'required|desc:订单ID',
			],
			'getOrderDetail' => [
				'id' => 'required',
			],
		];
	}

	/**
	 * @desc 获取订单详情
	 */
	public function getOrderDetail(Request $request) {
		return Access::service('GoodsOrderService')
			->with('id', $request->input('id'))
			->run('getOrderDetail');
	}

	/**
	 * @desc 生成商品订单
	 */
	public function generateGoodsOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("生成商品订单:|" . $user_id);
		$goodsClass = $request->input('goods_class');
		if (!$goodsClass) {
			$goodsClass = '10';
		}
		$fromWay = $request->input('from_way');
		if (!$fromWay) {
			$fromWay = '10';
		}
		return Access::service('GoodsOrderService')
			->with('goods_id', $request['goods_id'])
			->with('number', $request['number'])
			->with('address', $request['address'])
			->with('consignee_name', $request['consignee_name'])
			->with('consignee_mobile', $request['consignee_mobile'])
			->with('goods_type', $request['goods_type'])
			->with('goods_class', $goodsClass)
			->with('from_way', $fromWay)
			->with('user_id', $user_id)
			->run('generateGoodsOrder');
	}

	/**
	 * @desc 批量生成商品订单
	 */
	public function batchGenerateGoodsOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("批量生成商品订单:|" . $user_id);

		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('shop_id', $request['shop_id'])
			->with('address_id', $request['address_id'])
			->run('batchGenerateGoodsOrder');
	}

	/**
	 * @desc 获取商品订单列表
	 */
	public function getGoodsOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("获取商品订单:|" . $user_id);

		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('state', $request->input('state'))
			->with('pageSize', $request->input('pageSize'))
			->run('getGoodsOrder');
	}

	/**
	 * @desc 批量商品订单支付
	 */
	public function batchPayGoodsOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("批量订单支付:|" . $user_id);

		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('order_id', $request->input('order_id'))
			->run('batchPayGoodsOrder');
	}

	/**
	 * @desc 商品订单支付
	 */
	public function payGoodsOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("订单支付:|" . $user_id);

		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('order_id', $request->input('order_id'))
			->run('payGoodsOrder');
	}

	/**
	 * @desc 订单确认收货
	 */
	public function confirmGoods(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("确认收货:|" . $user_id);
		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('order_id', $request->input('order_id'))
			->run('confirmGoods');
	}

	/**
	 * @desc 取消订单
	 */
	public function cancelOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("取消订单:|" . $user_id);
		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('order_id', $request->input('order_id'))
			->run('cancelOrder');
	}

	/**
	 * @desc 订单删除
	 */
	public function delOrder(Request $request) {
		$user_id = $request->user()->claims->getId();
		Log::info("订单删除:|" . $user_id);
		return Access::service('GoodsOrderService')
			->with('user_id', $user_id)
			->with('order_id', $request->input('order_id'))
			->run('delOrder');
	}
}