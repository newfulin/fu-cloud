<?php
/**
 *
 */
namespace App\Modules\Finance\Repository;

use App\Common\Models\AcctUserTariffRate;
use App\Common\Contracts\Repository;
/**
 * 用户资费类
 */
class AcctUserTariffRateRepository extends Repository {

    public $model;

    public function __construct(AcctUserTariffRate $model)
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
    /**
     * 获取用户资费
     */
    public function getRateInfo($business_code,$tariff_code){
        $ret = $this->model->select('user_tariff_code','business_code','rate','max_rate',
        'base_rate','base_max_rate','status')->where('business_code','=',$business_code)
        ->where('user_tariff_code','=',$tariff_code)->first();
        return $ret;
    }
    
    public function save($data)
    {
        $this->model->insert($data);
    }

}