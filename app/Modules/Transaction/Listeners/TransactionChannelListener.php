<?php

namespace App\Modules\Transaction\Listeners;
use App\Modules\Access\Repository\WxPayOrderRepo;
use App\Modules\Callback\Callback;
use App\Modules\Finance\Finance;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Transaction\Repository\CoffeeConsumeOrderRepo;
use App\Modules\Transaction\Repository\TranOrderRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use App\Modules\Transaction\Repository\WithdrawOrderRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

// implements ShouldQueue
class TransactionChannelListener
{
    public $repo;
    public function __construct(CashOrderRepository $summary,WithdrawOrderRepo $detail,WxPayOrderRepo $wxPay)
    {
        $this->summary = $summary;
        $this->detail = $detail;
        $this->wxPay = $wxPay;

    }
    public function handle($event)
    {
        // 请求财务记账
        Log::info('=============================================='.'记账啊');
        $request = $event->request;

        Log::info('--------------------------------------------'.json_encode($request));
        Log::info('------------------merc_id--------------------------'.json_encode($request['detailParams']['acct_req_code']));
        Log::info('---------------------summaryId-----------------------'.json_encode($request['summaryId']));
        Log::info('-------------------detailId-------------------------'.json_encode($request['detailId']));
        Log::info('------------------trans_amt--------------------------'.json_encode($request['trans_amt']));

         $re = Finance::service('CashierService')
            ->with('code',$request['detailParams']['acct_req_code'])
            ->with('orderId',$request['summaryId'])
            ->with('detailOrderId',$request['detailId'])
            ->with('transAmount',$request['trans_amt'])
            ->run();
         Log::info('-------------------记账返回结果----------------'.$re);
         $params = array(
             'status' => '2',
             'update_time' => date("Y-m-d H:i:s"),
             'acct_res_code' => $re
         );

        $this->updateOrder($request['business_code'],$request['detailParams']['id'],$request['summaryParams']['id'],$params);
    }
    public function updateOrder($business_code,$detailId,$summaryId,$params)
    {
        DB::beginTransaction();
        try {
//            $this->$business_code($detailId,$params);

            Callback::service($business_code)
                ->with('detailId',$detailId)
                ->with('params',$params)
                ->run('update');

            Log::info('更新收银表 (原汇总表) | '.$detailId);
            $this->summary->update($summaryId,$params);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('数据修改失败', 9999);
        }
    }

}