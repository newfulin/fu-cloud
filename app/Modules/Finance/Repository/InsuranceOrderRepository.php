<?php
/**
 * 车险订单
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\InsuranceOrder;

class InsuranceOrderRepository extends Repository {

    public $model;

    public function __construct(InsuranceOrder $model)
    {
        $this->model = $model;
        $aa = [

        ];
    }
    /**
     * 获取车险订单明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        $this->find($Id);
        return $ret;
    }

    /**
     * 获取车险订单明细信息
     */
    public function getEntityByOuterOrderId($Id){
        $ret = $this->model->where('order_id','=',$Id)->first();
        $this->find($Id);
        return $ret;
    }
    
    public function save($data)
    {
        $this->model->insert($data);
    }

}