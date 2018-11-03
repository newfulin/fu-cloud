<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Models\TranOrder;
use App\Common\Contracts\Repository;

class TranOrderRepository extends Repository {

    public $model;

    public function __construct(TranOrder $model)
    {
        $this->model = $model;
        $aa = [

        ];
    }
    /**
     * 获取交易明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();

        //$this->find($Id);
        return $ret;
    }
    
    public function save($data)
    {
        $this->model->insert($data);
    }

}