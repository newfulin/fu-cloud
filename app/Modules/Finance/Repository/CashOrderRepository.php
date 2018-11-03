<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Models\CashOrder;
use App\Common\Contracts\Repository;

/**
 * Class CashOrderRepository
 * @package App\Modules\Finance\Repository
 * 收银流水
 */
class CashOrderRepository extends Repository {

    public $model;

    public function __construct(CashOrder $model)
    {
        $this->model = $model;
    }
    /**
     * 获取收银流水明细
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }

    public function getSummaryOrder($detail_id)
    {
        return optional($this->model->select('id','business_code','user_id','status')
            ->where('relation_id',$detail_id)
            ->first())
            ->toArray();
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