<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/28
 * Time: 15:04
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\ShelfProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShelfProductRepo extends Repository
{
    public function __construct(ShelfProduct $model)
    {
        $this->model = $model;
    }

    //随机产生四个车
    public function getFourCar()
    {
        $ret = optional(DB::table('shelf_product as t0')
        ->select('t0.id','t0.info_id','t0.car_status','t1.car_desc','t0.js_url','t2.url','t1.car_name','t1.order_price','t4.amount','t4.month_payment')

        ->leftJoin('car_info as t1',function ($join){
            $join->on('t0.info_id', '=', 't1.id');
        })
        ->leftJoin('car_pic as t2', function ($join){
            $join->on('t0.attr', '=', 't2.id');
        })
        ->leftJoin('car_brand as t3', function ($join){
            $join->on('t0.brand_id', '=', 't3.id');
        })
        ->leftJoin('price_car as t4', function ($join){
            $join->on('t0.price_id', '=', 't4.id');
        })
        ->where('t0.onshow','1')
        ->inRandomOrder()
        ->limit(4)
        ->get())
        ->toArray();

        if ($ret){
            foreach ($ret as $k =>$v)
            {
                $ret[$k]->url = R($v->url,false);
            }
        }

        return $ret;
    }

    //获取头条车辆信息
    public function getTopCarInfo($id)
    {
        $ret = optional(DB::table('shelf_product as t0')
            ->select('t0.id','t0.info_id','t0.car_status','t1.car_desc','t0.js_url','t2.url','t1.car_name','t1.order_price','t4.amount','t4.month_payment')

            ->leftJoin('car_info as t1',function ($join){
                $join->on('t0.info_id', '=', 't1.id');
            })
            ->leftJoin('car_pic as t2', function ($join){
                $join->on('t0.attr', '=', 't2.id');
            })
            ->leftJoin('car_brand as t3', function ($join){
                $join->on('t0.brand_id', '=', 't3.id');
            })
            ->leftJoin('price_car as t4', function ($join){
                $join->on('t0.price_id', '=', 't4.id');
            })
            ->where('t0.onshow','1')
            ->where('t0.id',$id)
            ->get())
            ->toArray();

        if(!$ret)Err('车辆信息不存在');
        $ret[0]->url = R($ret[0]->url,false);
        return $ret;
    }

}