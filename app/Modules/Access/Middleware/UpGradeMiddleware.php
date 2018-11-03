<?php

namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\UserUpGradeRepository;
use \Closure;

class UpGradeMiddleware extends Middleware
{
    // 所有参数
    private $params = array();
    // 随机字符串
    private $nonce_str;
    // 签名
    private $sign;
    // 商品描述
    private $body = '青橙智能';
    // 商户订单号
    private $out_trade_no;
    // 订单金额
    private $totalFee;
    // 终端IP
    private $spbill_create_ip;
    // 交易类型
    private $trade_type = 'NATIVE';
    // 支付结果回调通知地址
    private $notify_url = "https://boss.qcznkj.com/callback/wxnotify.php";

    /**
     * @param $totalFee string
     * @param Closure $next
     * @return mixed
     */
    public function handle($totalFee, Closure $next)
    {
        $out_trade_no = '1312321432432';
        $params = array(
            'Body' => $this->body,
            'Attach' => $this->attach,
            'Out_trade_no' => $out_trade_no,
            'Total_fee' => $totalFee['totalFee'],
            'Goods_tag' => $this->goods_tag,
            'Notify_url' => $this->notify_url,
            'Trade_type' => $this->trade_type,
            'Product_id' => rand(10000, 19999),
        );
        // 调用统一下单
        $ret = $this->UnifiedOrder($params);
        return $next($ret);
    }

    /**
     * 执行统一下单流程
     * @param $params array
     */
    public function UnifiedOrder($params)
    {
        //  发起下单请求，获取返回数据
        /*
        $PayOrder = new Service_PayOrder();
        $ret = $PayOrder->PayCodeOrder($params);
        $result = array();
        $result['appid']        = $ret['appid'];
        $result['partnerid']    = $ret['mch_id'];
        $result['noncestr']     = $ret['nonce_str'];
        $result['prepayid']     = $ret['prepay_id'];
        $result['timestamp']    = time();
        $result['package']      = "Sign=WXPay";
        $result['sign']         = $this->makeSign($result);
        $result['out_trade_no'] = $out_trade_no;
        if ($_SERVER['REMOTE_ADDR'] == '::1') {
            $spbill_create_ip = '127.0.0.1';
        } else {
            $spbill_create_ip=$_SERVER['REMOTE_ADDR'];//终端ip
        }
        */
        $result = array(
            'appid' => '666666',
            'mch_id' => '88888888',
            'nonce_str' => '542352523',
            'prepay_id' => '7777777',
            'timestamp' => time(),
            'package' => 'Sign=UserUpGradeRepository',
            'sign' => $this->makeSign($params),
            'out_trade_no' => $params['Out_trade_no'],
            'body' => $params['Body'],
            'total_fee' => $params['Total_fee'],
        );
        return $result;

    }

    /**
     * @param $params array
     * @return string
     */
    public function makeSign($params)
    {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        // $string = $string . "&key=".$this->key;
        $string = $string . "&key=A0BBF31EE653830CD8D1F678DED66D1C";
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
    public function ToUrlParams($params)
    {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }
}
