<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 10:09
 */

namespace App\Modules\Access\Repository;



use App\Common\Contracts\Repository;
use App\Common\Models\FormulaModel;
use Illuminate\Support\Facades\DB;

class FormulaModelRepo extends Repository
{
    public function __construct(FormulaModel $model)
    {
        $this->model = $model;
    }

    public function getModelList()
    {
        $ret = optional($this->model
                ->select('id','name','param_index','param_info','glass_capacity')
                ->get())->toArray();
        return $ret;
    }
}