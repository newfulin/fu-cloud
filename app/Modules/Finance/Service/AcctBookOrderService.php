<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/2
 * Time: 11:00
 */
namespace App\Modules\Finance\Service ;

use App\Common\Contracts\Service;
use App\Modules\Finance\Middleware\UtilDictMiddle;
use App\Modules\Finance\Repository\AccountRepository;
use App\Modules\Finance\Repository\AcctBookingOrderRepository;


/**
 * 账单服务
 */
class AcctBookOrderService extends Service{

    public $repo;
    public function __construct(AcctBookingOrderRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        UtilDictMiddle::class => [
            'only' => ['getBookOrderList']
        ]
    ];

    /**
     * 查询账单
     * */
    public function getBookOrderList(AcctBookingOrderRepository $repo,$request)
    {
        $ret = $repo->getBookOrderList($request);

        foreach ($ret as $key => $val){
            $ret[$key]['ytd'] = date('Y-m-d',strtotime($val['booking_time']));
            $ret[$key]['his'] = date('H:i',strtotime($val['booking_time']));
        }

        $request['list'] = $ret;
        $request['dict'] = 'dict.acct_booking_order';
        return $request;
    }

    /*
     * 查询资产
     */
    public function getAllBalance(AccountRepository $repo,$request)
    {
        $dataSet = $repo->getBalanceList($request['acct_id'],$request['user_id']);
        if (empty($dataSet)) {
            Err('ACC_NO_EXIST');
        }

        $result = array();
        foreach ($dataSet as $key => $value) {
            $ret = $this->getUnBookingAmount($request['acct_id'],$request['user_id'],$value['account_type'],$value['account_object']);
            $result[$key]['balance'] =  sprintf("%.2f", $value['balance']+$ret);
            //code 替换 msg
            switch ($value['account_type']) {
                case config('const_finance.ACCOUNT_TYPE_ASSET.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_ASSET.code');
                    $result[$key]['account_type_name'] = config('const_finance.ACCOUNT_TYPE_ASSET.msg');
                    break;
                case config('const_finance.ACCOUNT_TYPE_CREDIT.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_CREDIT.code');
                    $result[$key]['account_type_name'] = config('const_finance.ACCOUNT_TYPE_CREDIT.msg');
                    break;
                case config('const_finance.ACCOUNT_TYPE_FREEZE.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_FREEZE.code');
                    $result[$key]['account_type_name'] = config('const_finance.ACCOUNT_TYPE_FREEZE.msg');
                    break;
                case config('const_finance.ACCOUNT_TYPE_LEND.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_LEND.code');
                    $result[$key]['account_type_name'] = config('const_finance.ACCOUNT_TYPE_LEND.msg');
                    break;
                case config('const_finance.ACCOUNT_TYPE_POINTS.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_POINTS.code');
                    // $result[$key]['account_type_name'] = DIMsg('account', "ACCOUNT_TYPE_POINTS");
                    $result[$key]['account_type_name'] = '蚂蚁信用';
                    break;
                case config('const_finance.ACCOUNT_TYPE_REWARD.code'):
                    $result[$key]['account_type'] = config('const_finance.ACCOUNT_TYPE_REWARD.code');
                    $result[$key]['account_type_name'] = config('const_finance.ACCOUNT_TYPE_REWARD.msg');
                    break;
            }
        }
        return $result;

    }

    /**
     * 计算未记账余额
     * @param String $balance 账户余额
     * @param Array  $unBookOrder 未记账流水
     */
    public function getUnBookingAmount($acct_id,$code, $type,$obj)
    {
        $ret = sprintf("%.2f", 0);
        //未记账流水
        $unBookOrder = $this->repo->getUnBookingOrderByProccessId($acct_id,$code, $type,$obj);

        if ($unBookOrder) {
            //有未记账流水
            //获得 有借贷方向的余额
            foreach ($unBookOrder as $key => $value) {
                switch ($value['account_category']) {
                    case config('const_finance.CATEGORY_ASSET.code');
                    case config('const_finance.CATEGORY_EXPENSES.code');
                        // 资产和费用的情况：借方表示增加 贷方表示减少
                        // 余额计算
                        $ret = $ret + $value['debit_amount'] - $value['credit_amount'];
                        break;
                    case config('const_finance.CATEGORY_LIABILITIES.code');
                    case config('const_finance.CATEGORY_INCOME.code');
                    case config('const_finance.CATEGORY_REVENUE.code');
                    case config('const_finance.CATEGORY_OWNERS.code');
                        $ret = $ret +  $value['credit_amount'] - $value['debit_amount'] ;
                        break;
                }
            }
        }
        return $ret;
    }
}