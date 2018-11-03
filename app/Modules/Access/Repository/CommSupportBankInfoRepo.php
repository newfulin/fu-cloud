<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 13:49
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommSupportBankInfo;

class CommSupportBankInfoRepo extends Repository
{
    public function __construct(CommSupportBankInfo $model)
    {
        $this->model = $model;
    }

    public function getSupportBankList()
    {
        $ret = optional($this->model
            ->select('id','bank_id','bank_name')
            ->where('status',1)
            ->get())
            ->toArray();
        return $ret;
    }
}