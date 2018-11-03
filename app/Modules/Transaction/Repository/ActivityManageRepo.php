<?php
namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\ActivityManage;
use Illuminate\Support\Facades\DB;


class ActivityManageRepo extends Repository
{
    public function __construct(ActivityManage $model)
    {
        $this->model = $model;
    }
    public function getUserControl($id)
    {
        $re = optional($this->model
            ->select('user_control')
            ->where('id',$id)
            ->where('status','01')
            ->first())
            ->toArray();
        if (!$re) {
            Err('请选择有效的活动','7777');
        }
        return $re;
    }
    public function getActUsage($id)
    {
        $re = optional($this->model
            ->select('use_desc')
            ->where('id',$id)
            ->first())
            ->toArray();
        if (!$re) {
            Err('活动信息错误','7777');
        }
        return $re;
    }
    public function shareCoupon($id)
    {
        $re = optional($this->model
            ->select('title','desc')
            ->where('id',$id)
            ->first())
            ->toArray();
        if (!$re) {
            Err('活动信息错误','7777');
        }
        return $re;
    }
    public function getShareEachPrice($id)
    {
        $re = optional($this->model
            ->select('share_each_price','user_control','start_data','end_data')
            ->where('id',$id)
            ->first())
            ->toArray();
        if (!$re) {
            Err('活动信息错误','7777');
        }
        return $re;
    }
    public function checkActInfo($id)
    {
        $re = optional($this->model
            ->select('share_each_price','user_control','start_data','end_data','status')
            ->where('id',$id)
            ->first())
            ->toArray();
        if (!$re) {
            Err('活动信息错误','7777');
        }
        return $re;
    }

    /**
     * @desc 获取活动信息
     * @param $act_id
     */
    public function getCouponInfo($id)
    {
        return optional($this->model
            ->select('title','setting','card_amount','buy_amount','detail','resale')
            ->where('id',$id)
            ->first())
            ->toArray();
    }
    public function getMoneyInfo($id)
    {
        $re = optional($this->model
            ->select('card_amount','buy_amount')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $re;
    }
    public function getUserAct($user_id)
    {
        return optional(
            DB::table('activity_manage as t0')
            ->select('t0.id')
            ->leftJoin('card_detail as t1', function ($join) {
                $join->on('t0.id', '=', 't1.act_id');
            })
            ->where('t1.user_id', $user_id)
            ->get())
            ->toArray();
    }

    public function getActList($pageSize, $status)
    {
        $sql = $this->model
            ->select('id','title','start_data','end_data','type','acct_count','status','img_url','buy_amount','card_amount','url_type','setting','img_url_out')
            ->where('status1','00');
        if ($status) {
            $sql->where('status',$status);
        }
        $ret = optional($sql->orderBy('start_data','desc')->paginate($pageSize))->toArray();
        return $ret['data'];
    }


    public function getActInfo($id)
    {
        $ret = optional($this->model
            ->select('id','title','start_data','end_data','type','acct_count','status','img_url','buy_amount','card_amount','setting','desc','max_amount','img_url_out','share_each_price','user_control')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $ret;
    }
    public function updateAcct($id)
    {
        $this->model->where('id',$id)->increment('acct_count');
        return ;
    }
    // 获取卡券有效期
    public function getValid($id)
    {
        $ret = optional($this->model
            ->select('status')
            ->where('id',$id)
            ->first())
            ->toArray();
        return $ret['status'];
    }

    public function transferResale($act_id)
    {
        $ret = optional($this->model
            ->select('resale')
            ->where('id',$act_id)
            ->first())
            ->toArray();
        return $ret['resale'];
}

}
