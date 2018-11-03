<?php
namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CardDetail;
use Illuminate\Support\Facades\DB;


class CardDetailRepo extends Repository
{
    public function __construct(CardDetail $model)
    {
        $this->model = $model;
    }
    public function getCode($id)
    {
        $re = $re = optional($this->model
            ->select('business_code')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $re['business_code'];
    }
    public function updCardUser($cardId,$params)
    {
        return $this->model->where('id',$cardId)->update($params);
    }
    public function checkCard($cardId)
    {
        $re = optional($this->model
            ->select('user_id','ouserid')
            ->where('id',$cardId)
            ->first())
            ->toArray();
        return $re;
    }
    public function getBuyCount($act_id)
    {
        $re = optional($this->model
            ->select('id')
            ->where('act_id',$act_id)
            ->get())
            ->toArray();
        return count($re);
    }
    public function toExamine($id)
    {
        $ret = optional(
            DB::table('card_detail as t0')
                ->select('t0.reason','t0.destination','t0.act_id','t1.use_desc','t1.card_amount')
                ->leftJoin('activity_manage as t1', function ($join) {
                    $join->on('t0.act_id', '=', 't1.id');
                })
                ->where('t0.id', $id)
                ->get())
            ->toArray();
        if (!$ret){
            Err('卡券信息错误，请稍后重试','7777');
        }
        return $ret[0];
    }
    public function getDestination($destination, $act_id)
    {

        $re = optional($this->model
            ->select('id')
            ->where('destination',$destination)
            ->where('act_id',$act_id)
            ->first())
            ->toArray();
        return $re;
    }
    public function getUserCoupon($user_id, $act_id, $status, $pageSize)
    {
        $sql = DB::table('card_detail')
            ->select('id','destination','end_data','status','act_id','condition','business_code','resale')
            ->where('user_id',$user_id);
        if ($act_id != null) {
            $sql->where('act_id',$act_id);
        }
        if ($status != '00' && $status != null) {
            $sql->where('status',$status);
        }
        if ($status == '00') {
            $sql->where(function ($query)  {
                $query->where('status', '=', '00')
                    ->orWhere('status', '=', '03');
            });
        }

        $ret = optional($sql->paginate($pageSize))->toArray();
        return $ret['data'];
    }

    public function useCardDetail($id, $status, $destination = '0', $reason = '0')
    {
        $params = array(
            'status' => $status,
            'update_time' => date("Y-m-d H:i:s"),
        );
        if ($destination != '0')
        {
            $params['destination'] = $destination;
        }
        if ($reason != '0')
        {
            $params['reason'] = $reason;
        }
        return $this->model->where('id', $id)->update($params);
    }
    public function insertCardDetail($request)
    {
        $ret = $this->model->insert($request);
        return $ret;
    }
    public function getActId($user_id)
    {
        return optional($this->model
            ->select('act_id')
            ->where('user_id',$user_id)
            ->where('status','00')
            ->get())
            ->toArray();
    }
    public function getCardNum($user_id,$act_id)
    {
        $re = optional($this->model
            ->select('id')
            ->where('user_id',$user_id)
            ->where('act_id',$act_id)
            ->get())
            ->toArray();
        return count($re);
    }


}
