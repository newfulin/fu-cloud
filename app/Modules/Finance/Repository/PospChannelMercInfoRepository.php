<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\PospChannelMercInfo;
/**
 * 通道商户信息
 */
class PospChannelMercInfoRepository extends Repository {

    public $model;

    public function __construct(PospChannelMercInfo $model)
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
     * 获取通道商户信息
     */
    public function getChannelMerc($merc_id, $channel_id, $merc_type)
    {
        $ret = $this->model->select('merc_id','merc_name','merc_type','status','channel_id','rate_id')
                    ->where('merc_id','=', $merc_id)
                    ->where('channel_id','=',  $channel_id)
                    ->where('merc_type','=',  $merc_type)
                    ->where('status','=',  10)
                    ->orderby('update_time', 'DESC')
                    ->first();
        return $ret;
    }

}