<?php
namespace Nxp\Wechat;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\WxPayOrderRepo;
use App\Modules\Finance\Repository\CashOrderRepository;
use Illuminate\Support\Facades\Log;

/**
 * 关于企业付款的说明
 */
header('Content-type:text/html; Charset=utf-8');


class MerchantPay
{
    public $data = [];

    //  商品订单号
    public function setPartnerTradeNo($partner_trade_no)
    {
        $this->data['partner_trade_no'] = $partner_trade_no;
        return $this;
    }
    //  付款金额，单位:元
    public function setAmount($amount)
    {
        $this->data['amount'] = $amount;
        return $this;
    }

    //  收款用户姓名
    public function setReUserName($re_user_name)
    {
        $this->data['re_user_name'] = $re_user_name;
        return $this;
    }
    //  备注
    public function setDesc($desc)
    {
        $this->data['desc'] = $desc;
        return $this;
    }

    //  OpenId
    public function setOpenid($openid)
    {
        $this->data['openid'] = $openid;
        return $this;
    }


    public function merchantPay()
    {

        $data = $this->data;
        Log::info('商户付款------------');
        $result = $this->createJsBizPackage($data);
        Log::info('返回结果------------'.json_encode($result));

        return $result;
    }


    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 发送红包
     * @param string $openid 用户在该公众号下的Openid
     * @param float $totalFee 红包金额 单位元
     * @param string $outTradeNo 订单号
     * @param string $orderName 红包发送者名称
     * @param string $wishing 祝福语
     * @param string $actName 互动名称
     * @return string
     */
    public function createJsBizPackage($data)
    {
        $publicParams = app()->make(CommCodeMasterRepo::class)->getConfigure('wxconfig_public','wx');
        $mchid = $publicParams['property5'];     //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = $publicParams['property2'];     //微信支付申请对应的公众号的APPID
        $appKey = $publicParams['property3'];    //微信支付申请对应的公众号的APP Key
        $apiKey = $publicParams['property4'];    //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        Log::info('--------------参数获取-----------'.json_encode($data));
        if ($_SERVER['REMOTE_ADDR'] == '::1') {
            $ip = '127.0.0.1';
        } else {
            $ip =$_SERVER['REMOTE_ADDR'];
        }

        $unified = array(
            'mch_appid' => $appid,// 商户账号appid
            'mchid' => $mchid,// 商户号
            'nonce_str' => $this->createNonceStr(),
            'partner_trade_no' => $data['partner_trade_no'],// 商户订单号
            'openid' => $data['openid'],// 用户openid

            // 校验用户姓名选项：NO_CHECK 不校验真实姓名，FORCE_CHECK 强校验真实姓名
            'check_name' => 'NO_CHECK',

//            're_user_name' => $data['re_user_name'],// （可选）收款用户真实姓名。FORCE_CHECK时 则必填

            'amount' => intval($data['amount'] * 100),// 金额（单位 转为分）
            'desc' => $data['desc'],// 企业付款备注，如为中文注意转为UTF8编码
            'spbill_create_ip' => $ip,// Ip地址

        );
        Log::info('--------------错误---------------------------------------------------------------');

        $unified['sign'] = $this->getSign($unified, $apiKey);


        Log::info('-----$unified----'.json_encode($unified));

        $postData = $this->arrayToXml($unified);
        $responseXml = $this->curlPost('https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers', $postData);

        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);

        Log::info('-----$unifiedOrder----'.json_encode($unifiedOrder));


        if ($unifiedOrder === false) {
            Log::info('parse xml error');
            $this->upOrderStatus($data['partner_trade_no'],'3',$unified['sign'],$ip);
            Err('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            Log::info('--return_code--'.json_encode($unifiedOrder->return_msg));

            $this->upOrderStatus($data['partner_trade_no'],'3',$unified['sign'],$ip);

            Err('return_code false');

        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            Log::info('--result_code--'.json_encode($unifiedOrder->err_code));

            $this->upOrderStatus($data['partner_trade_no'],'3',$unified['sign'],$ip);

            Err('result_code false');
        }
        Log::info('-----success');
        $this->upOrderStatus($data['partner_trade_no'],'2',$unified['sign'],$ip);

        return 'success';
    }
    // 修改订单状态 ------------------
    public function upOrderStatus($id,$status,$sign,$ip)
    {
        $params = [
            'sign' => $sign,
            'spbill_create_ip' => $ip,
            'status' => $status,
        ];
         app()->make(WxPayOrderRepo::class)->update($id,$params);
         $arr = [
            'status' => $status,
         ];
         app()->make(CashOrderRepository::class)->update($id,$arr);
         return '';
    }
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        Log::info('-----工作目录------'.getcwd());


        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,'../storage/cert/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,'../storage/cert/apiclient_key.pem');
        //第二种方式，两个文件合成一个.pem文件
//        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
?>