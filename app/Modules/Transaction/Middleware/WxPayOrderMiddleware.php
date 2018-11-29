<?php
namespace App\Modules\Transaction\Middleware;
use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\WxPayOrderRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class WxPayOrderMiddleware extends Middleware
{
    public $repo;
    public function __construct( WxPayOrderRepo $repo)
    {
        $this->repo = $repo;
    }
    public function handle($request, Closure $next)
    {
        $result = $request['result'];
        // TODO: Implement handle() method.
        $WeChatParams = array(
            'id' => $result['out_trade_no'],
            'sign' => $result['sign'],
            'body' => $result['body'],
            'total_fee' => $result['total_fee'],
            'spbill_create_ip' => $result['spbill_create_ip'],
            'time_start' => date("Y-m-d H:i:s", $request['time']),
            'time_expire' => date("Y-m-d H:i:s", $request['time'] + 600),
            'create_time' => date("Y-m-d H:i:s", $request['time']),
            'create_by' => 'system',
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'update_by' => 'system',
            'prepayid' => $result['prepayid'],
            'state' => '1',
            'status' => '1',
            'user_id' => $request['user_id'],
            'trans_amt' => $request['trans_amt'],
            'trans_time' => date("Y-m-d H:i:s", $request['time']),
            'user_name' => '',
            'business_code' => $request['business_code'],
            'receive_amt' => $request['receive_amt']['receiveAmt'],
            'acct_req_code' => config('interface.FINANCE.'.$request['business_code']),
            'channel_rate' => $request['rateInfo']['rate'],        // 通道费率
            'channel_id' => $request['channelInfo']->channel_id,   // 通道ID
            'channel_merc_id' => $request['channelInfo']->merc_id, // 通道商户编号
            'recharge_amt' => isset($request['recharge_amt']) ? $request['recharge_amt'] : 0,
            'relation_id' => $request['summaryId'],

        );
        $params = array();
        $params['appid']        = $result['appid'];
        $params['partnerid']    = $result['partnerid'];
        $params['noncestr']     = $result['noncestr'];
        $params['prepayid']     = $result['prepayid'];
        $params['timestamp']    = $request['time'];
        $params['package']      = "Sign=WXPay";
        $params['sign']         = $request['sign'];
        $params['out_trade_no'] = $result['out_trade_no'];

        if (isset($result['code_url'])) {
            $params['code_url'] = $result['code_url'];
        }
        $request['params'] = $params;

        $request['detailId'] = $result['out_trade_no'];
        $request['summaryId'] = ID();
        $request['WeChatParams'] = $WeChatParams;
        $this->repo->insert($WeChatParams);
        Log::info('========================================'.json_encode($params));
        return $next($request);
    }
}