<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/25
 * Time: 9:56
 */
//微信下单
namespace App\Modules\Transaction\Middleware;


use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class WeChatOrderMiddle extends Middleware
{
    public function handle($request, Closure $next)
    {
        $notifyUrl = '';
        $attach = $request['business_code'];

        $request['body'] = config('interface.DICT.'.$request['business_code'] . '.msg');

        $notifyUrl = config('parameter.SHARE.notifyUrl');

        $trans_amt = Money()->getYuan2Fen($request['trans_amt']);

//        $result = app('nxp-wechat')->pay()
//            ->setAppid()
//            ->setMchId()
//            ->setBody($request['body'])
//            ->setOutTradeNo($request['detailId'])
//            ->setTotalFee($trans_amt)
//            ->setSpbillCreateIp()
//            ->setNotifyUrl($notifyUrl)
//            ->setAttach($attach)
//            ->setTradeType()
//            ->setProductId()
//            ->setTimeStart()
//            ->setTimeExpire()
//            ->getOrder();


         $result = app('nxp-wechat')->payH5()
            ->setAppid()
            ->setTradeType()
            ->setMchId()
            ->setBody($request['body'])
            ->setOutTradeNo()
            ->setTotalFee($trans_amt)
            ->setSpbillCreateIp()
            ->setNotifyUrl($notifyUrl)
            ->setAttach($attach)
            ->setOpenId($request['open_id'])
            ->setProductId()
            ->setTimeStart()
            ->setTimeExpire()
            ->getOrder();

        $params = array();
        $params['appId']        = $result['appid'];
        $params['timeStamp']    = $request['time'];
        $params['nonceStr']     = $this->getNonceStr();
        $params['package']      = 'prepay_id=' . $result['prepayid'];
        $params['signType']     = "MD5";

        $request['sign'] = $this->makeSign($params);

        $result['noncestr'] = $params['nonceStr'];
        $request['result'] = $result;
        return $next($request);
    }

    /**
     * 生成签名
     *  @return 签名
     */
    public function makeSign($params) {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        Log::info('验签参数-------' . json_encode($params));
        $string = $this->ToUrlParams($params);

        //签名步骤二：在string后加入KEY
        // $string = $string . "&key=".$this->key;
        $string = $string . "&key=" . config('parameter.SHARE.key');
        Log::info('签名串-------' . $string);
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
    public function ToUrlParams($params) {
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

    public static function getNonceStr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}