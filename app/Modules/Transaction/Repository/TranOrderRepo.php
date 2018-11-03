<?php
namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\TranOrder;



class TranOrderRepo extends Repository
{
    public function __construct(TranOrder $model)
    {
        $this->model = $model;
    }
    public function getSummaryOrder($detail_id)
    {
        return optional($this->model->select('id','business_code','user_id')
            ->where('relation_id',$detail_id)
            ->first())
            ->toArray();
    }
    public function updateSummaryOrder($id,$params)
    {
        return $this->model->where('id', $id)->update($params);
    }
    public function insertSummaryOrder($request)
    {
        $ret = $this->model->insert($request);
        return $ret;
    }


}
