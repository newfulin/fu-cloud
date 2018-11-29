<?php
namespace App\Modules\Transaction\Middleware;
use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\WxPayOrderRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class MerchantPayMidOrder extends Middleware
{
    public $repo;
    public function __construct( WxPayOrderRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next)
    {
        // TODO: Implement handle() method.

        $codeFinance = config('interface.FINANCE.'.$request['business_code']);

        $WeChatParams = array(
            'id' => $request['detailId'],

//            'sign' => $result['sign'],
//            'body' => $result['body'],
//            'spbill_create_ip' => $result['spbill_create_ip'],
//            'prepayid' => $result['prepayid'], // 预支付标识

            'total_fee' => $request['trans_amt'],
            'time_start' => date("Y-m-d H:i:s", $request['time']),
            'time_expire' => date("Y-m-d H:i:s", $request['time'] + 600),
            'create_time' => date("Y-m-d H:i:s", $request['time']),
            'create_by' => 'system',
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'update_by' => 'system',
            'state' => '1',
            'status' => '1',
            'user_id' => $request['user_id'],
            'trans_amt' => $request['trans_amt'],
            'trans_time' => date("Y-m-d H:i:s", $request['time']),
            'user_name' => '',
            'business_code' => $request['business_code'],
            'receive_amt' => $request['receive_amt']['receiveAmt'],
            'acct_req_code' => $codeFinance,
            'channel_rate' => $request['rateInfo']['rate'],        // 通道费率
            'channel_id' => $request['channelInfo']->channel_id,   // 通道ID
            'channel_merc_id' => $request['channelInfo']->merc_id, // 通道商户编号
            'recharge_amt' => isset($request['recharge_amt']) ? $request['recharge_amt'] : 0
        );

        $request['WeChatParams'] = $WeChatParams;
        $request['codeFinance'] = $codeFinance;
        $this->repo->insert($WeChatParams);
        Log::info('--------生成微信流水---------');
        return $next($request);
    }
}