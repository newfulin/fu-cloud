<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 13:54
 */
namespace App\Modules\Finance\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\AcctAccountBalance;
/**
 * 账户余额
 */
class AcctAccountBalanceRepository extends Repository {

    public $model;

    public function __construct(AcctAccountBalance $model)
    {
        $this->model = $model;
    }

    /**
     * 获取交易明细信息
     */
    public function getEntity($Id){
        $ret = $this->model->where('id','=',$Id)->first();
        return $ret;
    }

    /**
     * 获取需要记账的账户
     */
    public function getAccountById($acct_id,$code,$obj,$type)
    {
        $ret = $this->model->select('id','account_id','account_type','account_object','process_id',
                        'balance','opening_balance','occurred_amount','closing_order','status',
                        'direction')
                    ->where('process_id','=', $code)
                    ->where('account_id','=', $acct_id)
                    ->where('account_object','=', $obj)
                    ->where('account_type','=',  $type)
                    ->where('status','=', 1)
                    ->first();
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
     * 保存插入数据
     */
    public function save($data)
    {
        $this->model->insert($data);
    }

}