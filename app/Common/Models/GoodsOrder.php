<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 17:50
 */

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsOrder extends Model {
	protected $table = "goods_order";
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	const CREATED_AT = 'create_time';
	const UPDATED_AT = 'update_time';

	protected $hidden = [
		'id', 'address_id',
	];
	protected $fillable = [
		'id', 'address_id',
	];

	// 商品ID 1-1 商品表ID   'goodsInfo','businessInfo'
	public function getAddressInfo() {
		return $this->hasOne(ReceiveAddress::class, 'id', 'address_id')->select('name', 'tel', 'area', 'address');
	}
}