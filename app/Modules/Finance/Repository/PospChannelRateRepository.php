<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\PospChannelRate;
/**
 * 通道费率信息
 */
class PospChannelRateRepository extends Repository {

    public $model;

    public function __construct(PospChannelRate $model)
    {
        $this->model = $model;
    }

    /**
     * 获取明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
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
     * 保存插入数据
     */
    public function save($data)
    {
        $this->model->insert($data);
    }
    /**
     * 获取通道费率
     */
    public function getChannelRateById($rate_id)
    {
        $ret = $this->model->select(
                    'id','channel_id','cost_rate','cost_max_rate','norm_rate','norm_max_rate',
                    'advance_rate','advance_max_rate','status' )
                ->where('id','=', $rate_id)
                ->where('status','=', 1)
                ->first();
        return $ret;

    }

}