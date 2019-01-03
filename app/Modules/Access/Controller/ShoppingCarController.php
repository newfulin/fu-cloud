<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/21
 * Time: 18:18
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class ShoppingCarController extends Controller
{
    public function getRules(){
        return [
            'addGoodsToCar' => [
                'goods_id' => 'required',
                'piece'    => 'required'
            ],
            'updateGoodsCar' => [
                'goods_id' => 'required',
                'num'      => 'required'
            ],
            'delGoodsCar' => [
                'id' => 'required'
            ]
        ];
    }

    /**
     * @获取我的购物车
     */
    public function getMyGoodsCar(Request $request){
        $user_id = $request->user()->claims->getId();
        return Access::service("ShoppingCarService")
            ->with('user_id', $user_id)
            ->run('getMyGoodsCar');
    }

    /**
     * @添加到购物车
     */
    public function addGoodsToCar(Request $request){
        $num = $request->input("num");
        $user_id = $request->user()->claims->getId();
        if (!$num) $num = 1;
        return Access::service('ShoppingCarService')
            ->with('user_id', $user_id)
            ->with('goods_id', $request->input('goods_id'))
            ->with('num', $num)
            ->with('piece', $request->input('piece'))
            ->run('addGoodsToCar');
    }
    /**
     * @更新购物车
     */
    public function updateGoodsCar(Request $request){
        $user_id = $request->user()->claims->getId();
        return Access::service('ShoppingCarService')
            ->with('user_id', $user_id)
            ->with('goods_id', $request->input('goods_id'))
            ->with('num', $request->input('num'))
            ->run('updateGoodsCar');
    }
    /**
     * @删除购物车
     */
    public function delGoodsCar(Request $request){
        return Access::service('ShoppingCarService')
            ->with('id',$request['id'])
            ->run('delGoodsCar');
    }

}