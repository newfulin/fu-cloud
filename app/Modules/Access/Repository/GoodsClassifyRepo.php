<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/6
 * Time: 12:53
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\GoodsClassify;

class GoodsClassifyRepo extends Repository
{

    public function __construct(GoodsClassify $model)
    {
        $this->model = $model;
    }

    /**
     * @dec 获取分类
     * @param 父级ID
     */
    public function getClassify($request){

        $ret = optional($this->model
            ->select("id","name","img")
            ->where("pid",$request['pid'])
            ->orderBy("sort","asc")
            ->get())
            ->toArray();

        return $ret;
    }
    public function getHomeClassify($request){
        $ret = optional($this->model
            ->select("id","name","img")
            ->where("pid","!=",0)
            ->limit($request['num'])
//            ->orderByRaw("RAND()")
            ->get())
            ->toArray();

        return $ret;
    }
}