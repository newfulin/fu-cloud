<?php
namespace Nxp\Wechat;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use Illuminate\Support\Facades\Log;


/**
 * 关于微信现金红包的说明
 * 1.微信现金红包要求必传证书，需要到https://pay.weixin.qq.com 账户中心->账户设置->API安全->下载证书，证书路径在第214行和217行修改
 * 2.默认的使用场景是抽奖（即scene_id参数为PRODUCT_2），额度是1-200元，所以测试时的最低金额是1元。如需修改在产品中心->产品大全->现金红包->产品设置中修改
 * 3.错误码参照 ：https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_4&index=3
 */
header('Content-type:text/html; Charset=utf-8');


class RedPacket
{
    public $data = null;


    public function redPacket()
    {

        //①、获取当前访问页面的用户openid（如果给指定用户发送红包，则填写指定用户的openid)

        $openId = 'o2Y6E1b8tA8J10gi3DT2yzVKqLX8';

        //②、发送红包
        $outTradeNo = uniqid();     //你自己的商品订单号
        $payAmount = 1;             //红包金额，单位:元
        $sendName = '美乐';          //红包发送者名称
        $wishing = '感谢！';         //红包祝福语
        $act_name='红包活动';        //活动名称


        $result = $this->createJsBizPackage($openId,$payAmount,$outTradeNo,$sendName,$wishing,$act_name);
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
    public function createJsBizPackage($openid, $totalFee, $outTradeNo, $sendName,$wishing,$actName)
    {
        $publicParams = app()->make(CommCodeMasterRepo::class)->getConfigure('wxconfig_public','wx');
        $mchid = $publicParams['property5'];     //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = $publicParams['property2'];     //微信支付申请对应的公众号的APPID
        $appKey = $publicParams['property3'];    //微信支付申请对应的公众号的APP Key
        $apiKey = $publicParams['property4'];    //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥

        $config = array(
            'mch_id' => $mchid,
            'appid' => $appid,
            'key' => $apiKey,
        );
        if ($_SERVER['REMOTE_ADDR'] == '::1') {
            $ip = '127.0.0.1';
        } else {
            $ip =$_SERVER['REMOTE_ADDR'];
        }
        $unified = array(
            'nonce_str' => $this->createNonceStr(),
            'mch_billno' => $outTradeNo,
            'mch_id' => $config['mch_id'],
            'wxappid' => $config['appid'],
            'send_name' => $sendName,
            're_openid' => $openid,
            'total_amount' => intval($totalFee * 100),       //单位 转为分
            'total_num'=>1,                 //红包发放总人数
            'wishing'=>$wishing,            //红包祝福语
            'client_ip' => $ip,
            'act_name'=>$actName,           //活动名称
            'remark'=>'remark',            //备注信息，如为中文注意转为UTF8编码
            'scene_id'=>'PRODUCT_2',      //发放红包使用场景，红包金额大于200时必传。
        );
        $unified['sign'] = $this->getSign($unified, $config['key']);

        Log::info('-----$unified----'.json_encode($unified));

        $postData = $this->arrayToXml($unified);
        $responseXml = $this->curlPost('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack', $postData);

        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);

        Log::info('-----$unifiedOrder----'.json_encode($unifiedOrder));


        if ($unifiedOrder === false) {
            Log::info('parse xml error');

            Err('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            Log::info('--return_code--'.json_encode($unifiedOrder->return_msg));
            Err('return_code false');

        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            Log::info('--result_code--'.json_encode($unifiedOrder->err_code));
            Err('result_code false');
        }
        Log::info('-----success');
        return 'success';
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