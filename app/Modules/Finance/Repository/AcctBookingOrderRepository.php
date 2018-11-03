<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\AcctBookingOrder;
use Illuminate\Support\Facades\Log;

class AcctBookingOrderRepository extends Repository {

    public $model;

    public function __construct(AcctBookingOrder $model)
    {
        $this->model = $model;
    }

    public function getShareUser($voucher_code,$shareAmount)
    {

        $count = optional($this->model->select('process_id')
            ->where('voucher_code', $voucher_code)
            ->where('account_object','80')
            ->get())
            ->toArray();

        $frequency = [];
        foreach ($count as $val) {
            $frequency[] = $val['process_id'];
        }
        // 统计数组中所有的值出现的次数
        $ary=array_count_values($frequency);
        $win = 0;
        foreach ($ary as $key => $val) {
            if ($shareAmount > $val) {
                $win++;
            }
        }
        $str = (string)($win / count($ary));
        $percent = substr($str,0,4) * 100;
        // 统计出最多的十个用户
        $num = [];
        for ($i = 0; $i < 10; $i++) {
            $max = max($ary);
            $over = array_search($max,$ary,false);
            $num[$i] = (string)$over;
            unset($ary[$over]);
        }
        $re = array(
            'num' => $num,
            'percent' => $percent,
        );
        return $re;
    }
    /**
     * @desc 统计用户记账单数（分享分润活动）
     * @param $user_id
     * @param $voucher_code
     * @return mixed
     */
    public function getShareAmount($user_id,$voucher_code)
    {
        $re = optional($this->model->select('id')
            ->where('process_id', $user_id)
            ->where('voucher_code', $voucher_code)
            ->get())
            ->toArray();
        return count($re);
    }
    /**
     * 获取记账流水
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }

    /**
     * 获取记账流水明细数根据关联明细ID
     */
    public function getCountByExternalConOrder($external_con_order){
        $ret = $this->model->where('external_con_order','=',$external_con_order)->count();
        return $ret;
    }

    /**
     * 更新数据
     */
    public function update($data,$Id)
    {
        $this->model->where('id','=',$Id)->update($data);
    }
    
    /**
     * 插入保存
     */
    public function save($data)
    {
        //log::info(json_encode($data));
        $this->model->insert($data);
    }

    /**
     * 获得需要记账的明细流水
     */
    public function getNeedBookOrder($reqCode,$batchId)
    {
        $ret  = optional($this->model->select('id','batch_id','batch_detail_id','account_id','voucher_code',
        'booking_time','account_category','account_type','account_object','process_id',
        'debit_amount','credit_amount','debit_credit_direction','account_status',
        'account_balance_status')
        ->where('voucher_code','=',$reqCode)
        ->where('account_balance_status','=','0')
        ->where('batch_id','=',$batchId)
        ->orderby('batch_id')->get())->toArray();
        //log::info(json_encode($ret));
        return $ret;
    }
    /**
     * 获得需要反记账的明细流水
     */
    public function getNegativePostingNeedBookOrder($reqCode,$batchId)
    {
        $ret = optional($this->model->select('id','batch_id','batch_detail_id','account_id','voucher_code','general_account_code','detail_account_code',
        'booking_time','account_category','account_type','account_object','process_id','remark',
        'debit_amount','credit_amount','debit_credit_direction','account_status','external_con_order',
        'account_balance_status')
        ->where('voucher_code','=',$reqCode)
        ->where('batch_id','=',$batchId)
        ->where('account_balance_status','=','1')
        ->orderby('batch_id')->get())->toArray();
        return $ret;
    }

    /**
     * 获取记账流水根据ID和流水状态
     */
    public function getBookOrderById($id,$status='1')
    {
        $ret = $this->model->select('id','batch_id','batch_detail_id','account_id','voucher_code',
                'booking_time','account_category','account_type','account_object','process_id',
                'debit_amount','credit_amount','debit_credit_direction','account_status',
                'account_balance_status')
                ->where('id','=',$id)
                ->where('account_balance_status','=',$status)//已经更新过的
                ->first();
        return $ret;    
    }

    /**
     * 根据process_id查账单
     */
    public function getBookOrderList($request)
    {
        $sql = $this->model
            ->select('id','booking_time','remark','process_id',
                'debit_amount','credit_amount','debit_credit_direction as direction',
                'account_balance_status','account_balance_time')
            ->where('process_id',$request['user_id']);
        if($request['direction'] != 0){
            $sql = $sql->where('debit_credit_direction',$request['direction']);
        }

        $ret = optional(
                $sql->orderBy('create_time','desc')
                ->paginate($request['pageSize']))
                ->toArray();

        return $ret['data'];
    }

    /**
     * 指定process_id,和account_type的未记账流水获得
     * account_status 10:制单，20：审核，30：记账
     * account_balance_status 0：未记账，1：已记账，2：记账中
     */
    public function getUnBookingOrderByProccessId($acct_id,$code, $type,$obj)
    {
        $ret = optional($this->model
            ->select(
                'account_type','process_id','debit_amount',
                'credit_amount','account_category' )
            ->where('process_id', $code)
            ->where('account_id',$acct_id)
            ->where('account_type', $type)
            ->where('account_object', $obj)
            ->where('account_status','30')
            ->where('account_balance_status',0)
            ->get())
            ->toArray();
        return $ret;
    }

}