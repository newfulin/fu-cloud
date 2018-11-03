<?php
/**
 * 咖啡交易明细流水
 */
namespace App\Modules\Finance\Repository;

use App\Common\Models\CoffeeConsumeOrder;
use App\Common\Contracts\Repository;

/**
 * 咖啡交易明细流水
 * Class CoffeeConsumeOrderRepository
 * @package App\Modules\Finance\Repository
 *
 */
class CoffeeConsumeOrderRepository extends Repository {

    public $model;

    public function __construct(CoffeeConsumeOrder $model)
    {
        $this->model = $model;
    }
    /**
     * 咖啡交易明细流水
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

}