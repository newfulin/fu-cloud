<?php
namespace App\Modules\Transaction\Service;
use App\Common\Contracts\Service;
use App\Modules\Access\Repository\WxPayOrderRepository as WeChatFlow;
use App\Modules\Finance\Finance;
use App\Modules\Transaction\Middleware\SummaryMiddleware;
use App\Modules\Transaction\Middleware\Trans\GetRateMiddleware;
use App\Modules\Transaction\Repository\CardDetailRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use App\Modules\Transaction\Repository\TranOrderRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Unirest\Request;

class ActivityTrans extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }
    public $middleware = [
        GetRateMiddleware::class=> [

        ],
        SummaryMiddleware::class => [

        ],
    ];
    public function cashBook($request)
    {

        Log::info('-------记账单---------'.json_encode($request));

        app()->make(TranOrderRepo::class)
            ->insertSummaryOrder($request['summaryParams']);
        // 请求财务记账，成功返回code'0000'，失败'false'
        Log::info('----------------请求财务记账-------------');
        Log::info('----参数设置-----|财务请求码='.config('interface.FINANCE.'. $request['business_code']).'---|记账单='.$request['summaryParams']['id'].'---|明细流水'.$request['detailId'].'---|交易金额'.$request['trans_amt']);
        $re = Finance::service('CashierService')
            ->with('code',config('interface.FINANCE.'. $request['business_code']))
            ->with('orderId',$request['summaryParams']['id'])
            ->with('detailOrderId',$request['detailId'])
            ->with('transAmount',$request['trans_amt'])
            ->run();
        Log::info('-------------------记账返回结果----------------'.$re);
        if ($re != '0000')
        {
            Err('财务记账请求失败','9999');
        }
        $summaryParams = array(
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'status' => '2'
        );
        app()->make(TranOrderRepo::class)
            ->updateSummaryOrder($request['summaryParams']['id'],$summaryParams);
        return $request;

    }
    public function handleOrder($data)
    {

        $detailId = $data['out_trade_no'];
        $result_code = $data['result_code'];
        $state = 2;
        if ($result_code != 'SUCCESS')
        {
            $state = 3;
        }
        $total_fee = Money()->getFen2Yuan($data['total_fee']);
        $time = $data['time'];

        // 查询明细流水
        $detailOrder = app()->make(TranTransOrderRepo::class)
            ->getDetailOrder($detailId);
        // 查询汇总流水
        $summaryOrder = app()->make(TranOrderRepo::class)
            ->getSummaryOrder($detailId);
        $trans_amt = $detailOrder['trans_amt'];
        $user_id = $summaryOrder['user_id'];



        // 判断交易额

        Log::info('交易金额判断'.'|订单金额='.$trans_amt.'|回调金额='.$total_fee);
        if ($total_fee != $trans_amt)
        {
            Err('交易金额不一致','9999');
        }

        // 请求财务记账，成功返回code'0000'，失败'false'
        Log::info('----------------请求财务记账-------------');
        Log::info('----参数设置-----|财务请求码='.config('interface.FINANCE.'. $detailOrder['business_code']).'---|汇总流水='.$summaryOrder['id'].'---|明细流水'.$detailId.'---|交易金额'.$trans_amt);

        $re = Finance::service('CashierService')
            ->with('code',config('interface.FINANCE.'. $detailOrder['business_code']))
            ->with('orderId',$summaryOrder['id'])
            ->with('detailOrderId',$detailId)
            ->with('transAmount',$trans_amt)
            ->run();
        Log::info('-------------------记账返回结果----------------'.$re);

        if ($re != '0000')
        {
            Err('财务记账请求失败','9999');
        }

        // 修改微信流水、明细流水、汇总流水、会员等级
        // 参数设置
        Log::info('------------------财务请求完成，修改流水、用户-------');
        $chatParams = array(
            'time_expire' => $time,
            'state' => $state
        );
        $detailParams = array(
            'update_time' => $time,
            'receive_time' => $time,
            'status' => $state,
            'outer_order_id' => $data['transaction_id']
        );
        $summaryParams = array(
            'update_time' => $time,
            'status' => $state
        );

        DB::beginTransaction();
        try {
            app()->make(WeChatFlow::class)
                ->updateWxOrder($detailId,$chatParams);
            app()->make(TranTransOrderRepo::class)
                ->updateDetailOrder($detailId,$detailParams);
            app()->make(TranOrderRepo::class)
                ->updateSummaryOrder($summaryOrder['id'],$summaryParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('订单修改失败', 9999);
        }
        return $user_id;
    }
}