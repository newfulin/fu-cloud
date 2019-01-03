<?php

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\GoodsInfo;
use App\Modules\Transfer\Transfer;
use Illuminate\Support\Facades\DB;

class GoodsInfoRepo extends Repository
{
    public function __construct(GoodsInfo $model)
    {
        $this->model = $model;
    }

    // 获取分享信息
    public function getShareInfo($id)
    {
        return optional($this->model
            ->select('title','content','logo','share_amount')
            ->where('id', $id)
            ->first())
            ->toArray();
    }
    // 获取商品购买记录信息
    public function buyRecord($request)
    {
        $re = optional(DB::table('goods_order as t0')
            ->select('t1.user_id','t1.headimgurl','t0.number','t0.create_time','t1.user_name','t0.state')
            ->leftJoin('comm_user_info as t1',function($join){
                $join->on('t0.user_id', '=', 't1.user_id');
            })
            ->where('t0.goods_id',$request['id'])
            ->whereIn('t0.state',['20','25','60'])
            ->paginate($request['pageSize']))
            ->toArray();
        return json_decode(json_encode($re['data']),true);
    }
    // 获取商品购买记录信息
    public function getBuyRecord($id)
    {
        $obj = optional(DB::table('goods_order as t0')
            ->select('t1.headimgurl')
            ->leftJoin('comm_user_info as t1',function($join){
                $join->on('t0.user_id', '=', 't1.user_id');
            })
            ->where('t0.goods_id',$id))
            ->whereIn('t0.state',['20','25','60'])
            ->get()
            ->toArray();
        $arr = json_decode(json_encode($obj),true);
        $re = [];
        foreach($arr as $k=>$v){
            $re[] = $v['headimgurl'];
        }
        return array_values(array_unique($re));

    }

    // 增加统计量
    public function addCount($id,$data)
    {
        return $this->model->where('id', $id)->update($data);
    }
    // 获取商品信息
    public function getGoodsInfo($id)
    {
        return optional($this->model
            ->select('name','img1','img2','img3','img4','img5','introduce','original_price','price','promote_profit','from_way','url','freight','sales','see','city','detail','status','goods_class','integral','goods_type')
            ->where('id', $id)
            ->first())
            ->toArray();
    }

    // 获取喜欢商品列表
    public function getLikeGoodsList()
    {
        $re = optional($this->model
            ->select('id', 'img','img1','img_list', 'name', 'introduce', 'price', 'sales','goods_class','integral')
            ->where('status', '10')
            ->inRandomOrder()
            ->limit(4)
            ->get())
            ->toArray();
        return $re;
    }

    // 获取商品列表
    public function getGoodsList($request)
    {
        $time = $request['time'];
        $sql = $this->model
            ->select('id', 'img', 'img1','img_list','name', 'introduce', 'price', 'sales','create_time','goods_class','integral')
            ->where('status', '10')
            ->where('goods_class', $request['goodsClass']);
        if ($request['purpose'] == 'new') {
            $sql = $sql->orderBy('sort','desc')->orderBy('create_time', 'desc');
        } else if ($request['purpose'] == 'yesterday') {
            $yesterday = $time - 24*60*60;
            $yesterday = date('Y-m-d',$yesterday);
            $sql = $sql->orderBy('create_time', 'desc')
                    ->where('create_time','like','%'.$yesterday.'%');
        } else if ($request['purpose'] == 'today') {
            /* 今日
            $today = date('Y-m-d',$time);
            $sql = $sql->orderBy('create_time', 'desc')
                    ->where('create_time','like','%'.$today.'%');
            */
            $sql = $sql->orderBy('create_time', 'desc');
        } else if ($request['purpose'] == 'past'){
            /*
            $year = date('Y',$time);
            $month = date('m',$time);
            $month = $month - 1;
            if ($month == 0) {
                $month = 12;
                $year = $year - 1;
            }
            $past = $year .'-'. $month;
            $sql = $sql->orderBy('sales', 'desc')
                    ->where('create_time','like','%'.$past.'%');
            */
            $sql = $sql->orderBy('sales', 'desc')->orderBy('sort','desc');
        } else if ($request['purpose'] == 'sales') {
            $sql = $sql->orderBy('sales','desc');
        } else if ($request['goodsType']) {
            $sql = $sql->where('goods_type',$request['goodsType']);
        } else if ($request['highlight']) {
            $sql = $sql->where('highlight',$request['highlight']);
        }


        $ret = optional($sql->paginate($request['pageSize']))->toArray();

        return $ret['data'];
    }

    public function setIncrementing($id){
        return $this->model
            ->where('id',$id)
            ->increment('sales');
    }

    public function setIncrementingNumber($id,$number){
        return $this->model
            ->where('id',$id)
            ->update(array(
                'sales' => DB::raw('sales + '.$number)
            ));
    }

    public function getRecommendGoods($request)
    {
        $ret = optional(
            $this->model->select('id','img','img_list','name','introduce','price', 'sales','create_time','original_price','province','city')
                        ->where('highlight','10')
                        ->where('status', '10')
//                        ->where('goods_class', '10')
                        ->orderBy('update_time','desc')
                        ->paginate($request['pageSize']))
                        ->toArray();
        return $ret['data'];
    }


    public function sortGoodsList($request)
    {
        $ret = optional(
            $this->model->select('id','img','img_list','name','introduce','price', 'sales','create_time','original_price','province','city')
                        ->where('goods_type',$request['goods_type'])
                        ->where('status', '10')
                        ->where('goods_class', '10')
                        ->orderBy('update_time','desc')
                        ->paginate($request['pageSize']))
                        ->toArray();
        return $ret['data'];
    }

    public function getSearchList($request)
    {
        $ret = $this->model
            ->select('id','img','img_list','name','introduce','price', 'sales','create_time','original_price','province','city','goods_class','integral')
            ->where('status', '10')
            ->where('goods_class',$request['goodsClass']);

            if($request['price']){
                $ret->where('price','>=',$request['price'][0]);
                $ret->where('price','<=',$request['price'][1]);
            }
            if($request['key_word']){
                $ret->where('name','like','%'.$request['key_word'].'%');
            }
        if($request['sales']){
            $ret->orderBy('sales',$request['sales']);
        }
        if($request['time']){
            $ret->orderBy('update_time',$request['time']);
        }
        if($request['sort_price']){
            $ret->orderBy('price',$request['sort_price']);
        }
        $ret = optional($ret->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];

    }

    public function getSearchName($request)
    {
        $ret = optional(
            $this->model->select('id','name','img')
                ->where('name','like','%'.$request['key_word'].'%')
                ->where('goods_class',$request['goodsClass'])
                ->where('status', '10')
                ->limit(5)->get())
            ->toArray();
        return $ret;
    }


    public function getStoreStatusByGoodsID($request){

        $ret = optional(
            DB::table('store as t0')
                ->select('t0.status')
                ->leftJoin('goods_info as t1',function ($join){
                    $join->on('t1.store_id','=','t0.id');
                })
                ->where('t1.id','=',$request['id'])
                ->first());
        return $ret;
    }

}