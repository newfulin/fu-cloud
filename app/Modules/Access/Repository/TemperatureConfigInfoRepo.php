<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 10:09
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CoffeeConfigInfo;
use App\Common\Models\TemperatureConfigInfo;
use Illuminate\Support\Facades\DB;

class TemperatureConfigInfoRepo extends Repository
{
    public function __construct(TemperatureConfigInfo $model)
    {
        $this->model = $model;
    }

    public function getTemperatureInfo($request)
    {
        $ret = $this->model->select('status')
            ->where('machine_id',$request['machine_id'])
            ->first();
        if(!$ret){
            $ret['status'] = 0;
            return $ret;
        }
        return $ret;
    }
}