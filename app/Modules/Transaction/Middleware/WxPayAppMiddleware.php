<?php

namespace App\Modules\Transaction\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\WxPayOrderRepository;
use Closure;
use Illuminate\Support\Facades\Log;

class WxPayAppMiddleware extends Middleware
{
    public $repo;

    public function __construct(WxPayOrderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function handle($request, Closure $next)
    {

        $result = $this->unifiedOrder($request);
        $request['result'] = $result;
        // TODO: Implement handle() method.
        $WeChatParams = array(
            'id' => $result['out_trade_no'],
            'sign' => $result['sign'],
            'body' => $result['body'],
            'total_fee' => $result['total_fee'],
            'spbill_create_ip' => $result['spbill_create_ip'],
            'time_start' => date("Y-m-d H:i:s", $request['time']),
            'time_expire' => date("Y-m-d H:i:s", $request['time'] + 600),
            'create_by' => 'system',
            'update_time' => date("Y-m-d H:i:s", $request['time']),
            'update_by' => 'system',
            'prepayid' => $result['prepayid'],
            'state' => '1',
            'user_id' => $request['user_id'],
        );
        $arr['appId'] = $result['appid'];
        $arr['signType'] = "MD5";
        $arr['nonceStr'] = $this->getNonceStr();
        $arr['package'] = 'prepay_id=' . $result['prepayid'];
        $arr['timeStamp'] = $request['time'];
        $params = array();
        $params['appid'] = $result['appid'];
        $params['partnerid'] = $result['partnerid'];
        $params['noncestr'] = $arr['nonceStr'];
        $params['prepayid'] = $result['prepayid'];
        $params['timestamp'] = $request['time'];
        $params['package'] = "Sign=WXPay";

        $params['sign'] = $this->makeSignApp($arr);
        $params['out_trade_no'] = $result['out_trade_no'];
        Log::info('========$params========' . json_encode($params));
        $request['params'] = $params;

        $request['detailId'] = $result['out_trade_no'];
        $request['summaryId'] = ID();
        $request['WeChatParams'] = $WeChatParams;
        Log::info('========================================' . json_encode($params));
        return $next($request);
    }

    public function unifiedOrder($request)
    {
        // ---------------------------------微信支付-------------------------------------------------------
        Log::info('------------resaleCoupon----------request-------------------' . json_encode($request));
        $openId = $request['openId'];
        $attach = $request['cardId'];
        $notifyUrl = config('parameter.SHARE.notifyUrl');
        $trans_amt = Money()->getYuan2Fen($request['trans_amt']);
        $result = app('nxp-wechat')->payH5()
            ->setAppid()
            ->setTradeType()
            ->setMchId()
            ->setBody('转售卡券')
            ->setOutTradeNo()
            ->setTotalFee($trans_amt)
            ->setSpbillCreateIp()
            ->setNotifyUrl($notifyUrl)
            ->setAttach($attach)
            ->setOpenId($openId)
            ->setProductId()
            ->setTimeStart()
            ->setTimeExpire()
            ->getOrder();
        Log::info('====resaleCoupon====' . json_encode($result));

        return $result;
    }

    /**
     * 生成签名
     *  @return 签名
     */
    public function makeSignApp( $params ){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        Log::info('验签参数-------'.json_encode($params));
        $string = $this->ToUrlParamsApp($params);
        //签名步骤二：在string后加入KEY
        // $string = $string . "&key=".$this->key;
        $string = $string . "&key=".config('parameter.SHARE.key');
        Log::info('签名串-------'.$string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParamsApp( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }

    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}