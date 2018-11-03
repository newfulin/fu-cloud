<?php
/**
 * 记账策略
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\AcctBookingPolicy;

class AcctBookingPolicyRepository extends Repository {

    public $model;

    public function __construct(AcctBookingPolicy $model)
    {
        $this->model = $model;
    }
    /**
     * 获取记账模板信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }

    /**
     * 获取记账模板列表信息
     */
    public function getBookingPolicyByVoucherCode($code){
        $ret = $this->model->where('request_code','=',$code)->orderby('sort')->get();
        return $ret;
    }
    
    /**
     * 更新数据
     */
    public function update($data,$Id)
    {
        $this->model->where('id','=',$Id)->update($data);
    }
    
    /**
     * 插入保存
     */
    public function save($data)
    {
        //log::info(json_encode($data));
        $this->model->insert($data);
    }

}