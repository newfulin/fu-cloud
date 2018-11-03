<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 13:57
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\SysCity;
use Illuminate\Database\Eloquent\Model;

class SysCityRepo extends Repository
{
    public function __construct(SysCity $model)
    {
        $this->model = $model;
    }

    /**
     * 省列表
     */
    public function getProvinceList()
    {
        $ret = optional($this->model
            ->select('id','name','parentid')
            ->where('parentid',0)
            ->orderBy('sort','asc')
            ->get())
            ->toArray();
        return $ret;
    }

    /**
     * 根据省ID,获取对应市列表
     */
    public function getCityListByProvinceId($provinceId)
    {
        $ret = optional($this->model
            ->select('id','name','parentid')
            ->where('parentid',$provinceId)
            ->where('status',1)
            ->orderBy('sort','asc')
            ->get())
            ->toArray();
        return $ret;
    }
}