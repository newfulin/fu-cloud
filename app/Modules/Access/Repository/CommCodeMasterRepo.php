<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/4/14
 * Time: 10:12
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommCodeMaster;

class CommCodeMasterRepo extends Repository
{
    public function __construct(CommCodeMaster $model)
    {
        $this->model = $model;
    }

    // 获取数据字典信息
    public function getConfigure($code,$key)
    {
        return optional($this->model
            ->select('property1','property2','property3','property4','property5'
            //    ,'property6','property7','property8','property9','property10'
            )
            ->where('code',$code)
            ->where('code_key',$key)
            ->first())
            ->toArray();
    }

    public function getConfigureByKey($code_key){
        return optional($this->model
            ->select('property1','property2','property3','property4','property5'
            )
            ->where('code_key',$code_key)
            ->first())
            ->toArray();
    }

    public function getConfigureById($id){
        return optional($this->model
            ->select('id','property1','property2','property3','property4','property5'
            )
            ->where('id',$id)
            ->first())
            ->toArray();
    }
}