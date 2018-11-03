<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/2
 * Time: 14:40
 */
namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\TranTransOrder;
use Illuminate\Support\Facades\DB;

class TranTransOrderRepo extends Repository{
    public function __construct(TranTransOrder $model)
    {
        $this->model = $model;
    }
    public function getDetailOrder($id)
    {
        return optional($this->model->select('id','trans_amt','business_code','status','user_id','invite_code')
            ->where('id',$id)
            ->first())
            ->toArray();
    }

    public function getDetailOrderInfo($id)
    {
        $ret =  DB::table('tran_trans_order as t0')
            ->select('t0.id','t0.trans_amt','t0.business_code','t0.status','t1.level_name','t1.code')
            ->leftJoin('invite_code as t1',function($join){
                $join->on('t0.invite_code','=','t1.code');
            })
            ->where('t0.id',$id)
            ->first();
        $ret =  json_encode($ret);
        return json_decode($ret,true);
    }
    /*
     * 查询流水
     * */
    public function getTransOrderByMercId($request)
    {
        $ret = optional(DB::table('cash_order as t0')->select('t0.id','t0.business_code','t0.trans_amt','t0.status','t0.create_time')
            ->leftJoin('wx_pay_order as t1',function($join){
                $join->on('t0.relation_id','=','t1.id');
            })
            ->leftJoin('tran_trans_order as t2',function($join){
                $join->on('t0.relation_id','=','t2.id');
            })
            ->leftJoin('withdraw_order as t3',function($join){
                $join->on('t0.relation_id','=','t3.id');
            })
            ->leftJoin('goods_pay_order as t4',function($join){
                $join->on('t0.relation_id','=','t4.id');
            })
            ->where('t0.user_id',$request['user_id'])
            ->where('t0.status','2')
            ->whereIn('t0.business_code',['A0130','A0131','A0132','A0230','A0231','A0233','A0140','A0150','A0160','A0600','A0700'])
            ->orderBy('t0.create_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }
    public function insertDetailOrder($request)
    {
        $ret = $this->model->insert($request);
        return $ret;
    }
    public function updateDetailOrder($id,$params)
    {
        return $this->model->where('id', $id)->update($params);
    }
    public function checkOuterOrderId($outer_order_id)
    {
        return optional($this->model
            ->select('id')
            ->where('outer_order_id',$outer_order_id)
            ->first())
            ->toArray();
    }

    //根据明细流水ID获取payID
    public function getPayId($id){
        return optional($this->model
            ->select('pay_id')
            ->where('id',$id)
            ->first())
            ->toArray();
    }

    public function update($id, $params)
    {
        return $this->model->where('id', $id)->update($params);
    }
}