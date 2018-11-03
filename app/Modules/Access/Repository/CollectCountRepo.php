<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 16:34
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CollectCount;
use Illuminate\Support\Facades\DB;

class CollectCountRepo extends Repository
{
    public function __construct(CollectCount $model)
    {
        $this->model = $model;
    }

    //我的收藏 咖啡
    public function getMyCollertGoodsList($request)
    {
        $ret = optional(DB::table('collect_count as t0')
            ->select('t1.id', 't1.img','t1.img1', 't1.name', 't1.introduce', 't1.price', 't1.sales', 't1.create_time')
            ->leftJoin('goods_info as t1',function($join){
                $join->on('t1.id','=','t0.obj_id');
            })
            ->where([
                't0.type'    => $request['type'],
                't0.user_id' => $request['user_id'],
                't0.status'  => '10'
            ])
            ->orderBy('t1.create_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }

    //判断收藏状态
    public function getCollertInfo($request)
    {
        return optional($this->model
            ->select('id')
            ->where([
                'user_id' => $request['user_id'],
                'obj_id' => $request['obj_id'],
                'status' => '10'
            ])
            ->first())
            ->toArray();
    }

    //获取收藏数量
    public function getCollectCount($request){
        return $this->model
            ->where([
                'obj_id' => $request['obj_id'],
                'status' => '10'
            ])
            ->count();
    }

    //取消车辆收藏
    public function cancelCollect($request)
    {
        return $this->model
            ->where([
                'obj_id' => $request['obj_id'],
                'user_id' => $request['user_id']
            ])
            ->update([
                'status' => '20'
            ]);
    }

    //获取全部收藏数量
    public function getMyCollectAllCount($request){
        return $this->model
            ->where([
                'user_id' => $request['user_id'],
                'status' => '10'
            ])
            ->count();
    }
}