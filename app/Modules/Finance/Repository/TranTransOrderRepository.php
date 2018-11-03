<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\TranTransOrder;

class TranTransOrderRepository extends Repository {

    public $model;

    public function __construct(TranTransOrder $model)
    {
        $this->model = $model;
    }
    /**
     * 获取交易明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }
    
    public function save($data)
    {
        $this->model->insert($data);
    }

}