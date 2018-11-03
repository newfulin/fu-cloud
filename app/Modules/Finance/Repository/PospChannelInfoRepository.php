<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\PospChannelInfo;
/**
 * 通道信息
 */
class PospChannelInfoRepository extends Repository {

    public $model;

    public function __construct(PospChannelInfo $model)
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

}