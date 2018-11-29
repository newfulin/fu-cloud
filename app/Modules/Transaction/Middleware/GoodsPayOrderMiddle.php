<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:12
 */

namespace App\Modules\Transaction\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\GoodsPayOrderRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class GoodsPayOrderMiddle extends Middleware
{
    public $repo;

    public function __construct(GoodsPayOrderRepo $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {
        $WeChatParams = [
//            'id' => $result['out_trade_no'],
//            'sign' => $result['sign'],
//            'body' => $result['body'],
//            'total_fee' => $result['total_fee'],
//            'spbill_create_ip' => $result['spbill_create_ip'],
            'time_start' => date("Y-m-d H:i:s", $request['time']),
            'time_expire' => date("Y-m-d H:i:s", $request['time'] + 600),
            'create_time' => date("Y-m-d H:i:s", $request['time']),
            'create_by' => 'system',
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'update_by' => 'system',
//            'prepayid' => $result['prepayid'],
            'order_class' => $request['order_class'],
            'state' => '1',
            'status' => '1',
            'user_id' => $request['user_id'],
            'trans_amt' => $request['trans_amt'],
            'trans_time' => date("Y-m-d H:i:s", $request['time']),
            'user_name' => '',
            'business_code' => $request['business_code'],
            'receive_amt' => $request['receive_amt']['receiveAmt'],
            'acct_req_code' => config('interface.FINANCE.' . $request['business_code']),
            'channel_rate' => $request['rateInfo']['rate'],        // 通道费率
            'channel_id' => $request['channelInfo']->channel_id,   // 通道ID
            'channel_merc_id' => $request['channelInfo']->merc_id, // 通道商户编号
            'order_id' => $request['order_id']
        ];
        if ($request['order_class'] == '10') {

            $result = $request['result'];

            $WeChatParams['id'] = $result['out_trade_no'];
            $WeChatParams['sign'] = $result['sign'];
            $WeChatParams['body'] = $result['body'];
            $WeChatParams['total_fee'] = $result['total_fee'];
            $WeChatParams['spbill_create_ip'] = $result['spbill_create_ip'];
            $WeChatParams['prepayid'] = $result['prepayid'];

            $params = [];
            $params['appid'] = $result['appid'];
            $params['partnerid'] = $result['partnerid'];
            $params['noncestr'] = $result['noncestr'];
            $params['prepayid'] = $result['prepayid'];
            $params['timestamp'] = $request['time'];
            $params['package'] = "Sign=WXPay";
            $params['sign'] = $request['sign'];
            $params['out_trade_no'] = $result['out_trade_no'];
            $params['code_url'] = '';
            $request['params'] = $params;

            $request['detailId'] = $result['out_trade_no'];
            Log::info('========================================' . json_encode($params));

        }

        if ($request['order_class'] == '20') {
            $WeChatParams['id'] = $request['detailId'];
        }
        $request['WeChatParams'] = $WeChatParams;
        $this->repo->insert($WeChatParams);

        return $next($request);
    }
}