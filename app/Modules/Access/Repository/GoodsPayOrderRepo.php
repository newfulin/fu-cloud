<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:08
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\GoodsPayOrder;
use Illuminate\Database\Eloquent\Model;

class GoodsPayOrderRepo extends Repository
{
    public function __construct(GoodsPayOrder $model)
    {
        $this->model = $model;
    }

    public function update($id, $attributes)
    {
        return $this->model->where('id',$id)->update($attributes);
    }

    //查询流水详情
    public function getDetailOrder($id)
    {
        return optional($this->model->select('id','user_id','trans_amt','business_code','order_id')
            ->where('id',$id)
            ->first())
            ->toArray();
    }
}