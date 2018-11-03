<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 08:40
 */

namespace App\Modules\Transaction\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Service\CommonService;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Transaction\Repository\TranOrderRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class SummaryMiddleware extends Middleware
{
    public $repo;

    public function __construct(CashOrderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        Log::info('下单成功，汇总流水参数设置');

        $summaryParams = array(
            'id' => $request['summaryId'],
            'relation_id' => $request['detailId'],
//            'trans_type' => '',
            'business_code' => $request['business_code'],

            'trans_amt' => $request['trans_amt'],
            'trans_time' => date("Y-m-d H:i:s", $request['time']),

            'channel_id' => $request['channelInfo']->channel_id, // 通道ID
            'channel_merc_id' => $request['channelInfo']->merc_id, // 通道商户编号

            'acct_req_code' => config('interface.FINANCE.'.$request['business_code']),

            'receive_amt' => $request['receive_amt']['receiveAmt'],

            'user_id' => $request['user_id'],
            'user_name' => '',
            'status' => '1',
            'merc_tariff_code' => $request['tariff_code'],

            'create_time' => date("Y-m-d H:i:s", $request['time']),
            'create_by' => 'system',
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'update_by' => 'system',
        );

        $this->repo->insert($summaryParams);
        $request['summaryParams'] = $summaryParams;

        return $next($request);

    }

}