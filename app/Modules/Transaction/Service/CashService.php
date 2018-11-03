<?php
/**
 * 阅读量统计
 * 队列异步接收,处理类库
 */

namespace App\Modules\Transaction\Service;

use App\Common\Contracts\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\Transaction\Repository\TranOrderRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use App\Modules\Finance\Finance;

class CashService extends Service
{

    public $repo;

    public function __construct(TranOrderRepo $summary,TranTransOrderRepo $detail)
    {
        $this->summary = $summary;
        $this->detail = $detail;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function handle($request)
    {
        // 请求财务记账
        Log::info('=============================================='.'保险记账'.json_encode($request));
        $re = Finance::service('CashierService')
            ->with('code',$request['business_code'])
            ->with('orderId',$request['summaryId'])
            ->with('detailOrderId',$request['detailId'])
            ->with('transAmount',$request['trans_amt'])
            ->run();
        Log::info('-------------------记账返回结果----------------'.$re);
        if ($re == '0000') {
            Log::info('-------------------成功----------------');
            $status = '2';
        } else {
            $status = '3';
        }
        $params = array(
            'status' => $status,
            'update_time' => date("Y-m-d H:i:s"),

        );
        $this->updateOrder($request['detailId'],$request['summaryId'],$params);
        return $re;
    }
    public function updateOrder($detailId,$summaryId,$params)
    {
        DB::beginTransaction();
        try {
            $this->detail->updateDetailOrder($detailId,$params);
            $this->summary->updateSummaryOrder($summaryId,$params);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('数据修改失败', 9999);
        }
    }
}