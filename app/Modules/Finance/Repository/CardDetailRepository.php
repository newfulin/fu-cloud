<?php
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CardDetail;
use Illuminate\Support\Facades\DB;

/**
 * 购车返现卡券
 */
class CardDetailRepository extends Repository
{
    public function __construct(CardDetail $model)
    {
        $this->model = $model;
    }

    /**
     * 获取卡券明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        //$this->find($Id);
        return $ret;
    }


}
