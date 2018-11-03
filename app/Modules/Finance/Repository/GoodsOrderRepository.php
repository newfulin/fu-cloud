<?php
/**
 * 咖啡交易明细流水
 */
namespace App\Modules\Finance\Repository;

use App\Common\Models\GoodsOrder;
use App\Common\Contracts\Repository;
use Illuminate\Support\Facades\DB;

/**
 * 购物订单表
 * Class GoodsOrderRepository
 * @package App\Modules\Finance\Repository
 *
 */
class GoodsOrderRepository extends Repository {

    public $model;

    public function __construct(GoodsOrder $model)
    {
        $this->model = $model;
    }
    /**
     * 购物订单表
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }
    
    public function save($data)
    {
        $this->model->insert($data);
    }

    public function update($id, $params)
    {
        return $this->model->where('id', $id)->update($params);
    }

    /**
     * 根据收银流水关联ID 获取订单明细信息
     * @param $id
     * @return mixed
     */
    public function getGoodOrder($relationId)
    {
        $ret = DB::select("select goods_order.* from goods_order,goods_pay_order
        where goods_order.id = goods_pay_order.order_id and 
        goods_pay_order.id = '{$relationId}'");
        return json_decode(json_encode($ret[0]),true);
    }

    /**
     * 根据商品ID获取商品详情
     * @param $id
     * @return mixed
     */
    public function getGoods($goodsId)
    {
        $ret = DB::select("select * from goods_info where goods_info.id = '{$goodsId}' ;");
        return json_decode(json_encode($ret[0]),true);
    }

}