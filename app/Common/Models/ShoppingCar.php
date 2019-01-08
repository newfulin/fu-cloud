<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/21
 * Time: 18:08
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCar extends Model {
	protected $table = "shopping_car";
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	const CREATED_AT = 'create_time';
	const UPDATED_AT = 'update_time';

	protected $hidden = [
		'create_time', 'update_time', 'goods_id', 'user_id',
	];

	// 商品ID 1-1 商品表ID   'goodsInfo','businessInfo'
	public function goodsInfo() {
		return $this->hasOne(GoodsInfo::class, 'id', 'goods_id')->select('id', 'name', 'img', 'store_id', 'goods_type');
	}
}