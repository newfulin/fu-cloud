<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */

namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Criteria\Criteria;
use App\Common\Models\AcctAccountBalance;

class AccountRepository extends Repository
{

    public function __construct(AcctAccountBalance $model)
    {
        $this->model = $model;
    }

    /**
     * @desc 创建账户数据
     */
    public function save($acct_id, $code, $obj, $type)
    {
        //检查是否有相同账户
        $this->checkAccount($acct_id,$code,$obj,$type);

        $this->model->id = ID();
        $this->model->account_id = $acct_id;
        $this->model->process_id = $code;
        $this->model->account_type = $type;
        $this->model->account_object = $obj;
        $this->model->balance = sprintf("%.2f", 0);
        $this->model->opening_balance = sprintf("%.2f", 0);
        $this->model->occurred_amount = sprintf("%.2f", 0);
        $this->model->status = 1;
        $this->model->create_by ="system";
        $this->model->update_by ="system";

        $ret = $this->model->save();
        return $ret;

    }

    /**
     * @desc 检查账户数据
     */
    public function checkAccount($acct_id, $code, $obj, $type)
    {
        $ret = optional($this->model->select('id')
                ->where('process_id', $code)
                ->where('account_id', $acct_id)
                ->where('account_object', $obj)
                ->where('account_type', $type)
                ->get())
                ->toArray();

        //已经有相同账户数据
        if($ret)
            Err("ACC_BALANCE_EXIT");
    }

    /*
     * 查询全部资产
     */
    public function getBalanceList($acct_id,$user_id){
        $ret = optional($this->model
            ->select('balance','account_type','account_object')
            ->where('process_id',$user_id)
            ->where('account_id',$acct_id)
            ->whereIn('account_type',[10,20,50,60])
            ->get())
            ->toArray();
        return $ret;
    }

    //获取指定账户指定类型的余额
    public function getBalance($request)
    {
        $ret = optional($this->model
            ->select('balance')
            ->where('process_id', $request['acct_code'])
            ->where('account_id', $request['acct_id'])
            ->where('account_object', $request['acct_obj'])
            ->where('account_type', $request['acct_type'])
            ->first())
            ->toArray();

        return $ret;
    }

}