<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 10:09
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\PriceProduct;
use Illuminate\Support\Facades\DB;

class PriceProductRepo extends Repository
{
    public function __construct(PriceProduct $model)
    {
        $this->model = $model;
    }

    public function getCoffeeList()
    {
       $ret = optional(DB::table('price_product as t0')
           ->select('t0.id','t0.eng_name','t0.rmb_price','t0.bean_price','t0.glass_pic','t0.product_pic','t0.desc','t0.name','t1.param_index','t1.param_info','t1.glass_capacity')
           ->leftJoin('formula_model as t1',function ($join){
               $join->on('t0.model_id', '=', 't1.id');
           })
           ->get())
           ->toArray();

        foreach($ret as $k=>$v){
            if($v->glass_pic){
                $v->glass_pic = R($v->glass_pic,false);
            }
            if($v->product_pic){
                $v->product_pic = R($v->product_pic,false);
            }
        }
        return $ret;
    }

    public function getProductIndex($productId)
    {
        $ret = optional(DB::table('price_product as t0')
            ->select('t0.id','t0.name','t1.param_index','t1.param_info')
            ->where('t0.id',$productId)
            ->leftJoin('formula_model as t1',function ($join){
                $join->on('t0.model_id', '=', 't1.id');
            })
            ->first());
        return $ret;
    }
}