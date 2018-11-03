<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 9:47
 */

namespace App\Modules\Transaction\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\WithdrawOrder;

class WithdrawOrderRepo extends Repository
{
    public function __construct(WithdrawOrder $model){
        $this->model = $model;
    }

    public function update($id, $params)
    {
        return $this->model->where('id', $id)->update($params);
    }
}