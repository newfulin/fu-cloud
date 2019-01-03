<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/21
 * Time: 18:46
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\ShoppingCar;
use Illuminate\Http\Request;


class ShoppingCarRepo extends Repository
{
    public $user_id;
    public function __construct(ShoppingCar $model,Request $request)
    {
        $this->model = $model;
        $request['user_id'] = $request->user()->claims->getId();
    }

    /**
     * 获取我的购物车
     * 商品名. 图 . 标题 . 数量 . 价格
     */
    public function getMyGoodsCar($id){
        $ret = optional($this->model
            ->where('user_id',$id)
            ->with('goodsInfo.businessInfo')
            ->select()
            ->get())
            ->toArray();
        return $ret;
    }

    /**
     * 添加购物车
     */
    public function addGoodsToCar($data){
        $check = optional($this->model
                ->where('user_id',$data['user_id'])
                ->where('goods_id',$data['goods_id'])
                ->first())
                ->toArray();
        // 判断存不存在 存在到Update
        if($check){
            $data['num'] = $check['num']+1;
            $this->updateGoodsCar($data);
        }else{
            $data['id'] = ID();
            $data['create_time'] = date('Y-m-d H:i:s');
            $ret = $this->model->insert($data);
            if (!$ret){
                Err("更新购物车失败");
            }
            return json_decode($ret);
        }
    }
    /**
     * 更新购物车
     */
    public function updateGoodsCar($data){
        $ret = $this->model
            ->where('user_id',$data['user_id'])
            ->where('goods_id',$data['goods_id'])
            ->update($data);
        if (!$ret){
            Err("更新购物车失败");
        }
        return json_decode($ret);
    }
    /**
     * 删除购物车
     */
    public function delGoodsCar($data){
        $ret = $this->model->where('id',$data['id'])->delete();
        if (!$ret){
            Err("删除购物车失败");
        }
        return json_decode($ret);
    }
}