<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\AcctBookingTemplet;

class AcctBookingTempletRepository extends Repository {

    public $model;

    public function __construct(AcctBookingTemplet $model)
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
    public function getBookingTempletByVoucherCode($code){
        $ret = $this->model->where('voucher_code','=',$code)
            ->where('use_status','=','1')
            ->orderby('voucher_batch_id')
            ->get();
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