<?php

// 商户付款
namespace App\Modules\Transaction\Middleware;


use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
use App\Modules\Access\Repository\WxUserInfoRepo;


class MerchantPayMid extends Middleware
{
    public $wx;
    public function __construct( WxUserInfoRepo $wx)
    {
        $this->wx = $wx;
    }

    public function handle($request, Closure $next)
    {
        Log::info('---------MerchantPayMid---------');
        if ($request['finance'] != '0000') {
            Err('--------记账未通过-------');
        }
        $openId = $this->wx->getWxInfo($request['user_id']);
        $result = '';
        if ($request['business_code'] == 'A0710') {
            // 红包-----------------------------------------------改动
            $result = app('nxp-wechat')->RedPacket()
                ->setMchBillNo('setMchBillNo')// 商户订单号
                ->setTotalAmount($request['trans_amt'])// 交易金额:元
                ->setSendName('setSendName')// 发送者
                ->setWishing('setWishing')// 祝福语
                ->setActName('setActName')// 活动名称
                ->setRemark('remark')// 备注
                ->setReOpenid($openId['openid'])// openId
                ->RedPacket();
            Log::info('-----微信红包-----'.json_encode($result));
        } else if ($request['business_code'] == 'A0711') {
            // 商户付款--------------------------------------------改动
            $result = app('nxp-wechat')->MerchantPay()
                ->setPartnerTradeNo($request['detailId'])// 商户订单号
                ->setAmount($request['trans_amt'])// 交易金额:元
                ->setDesc('remark')// 备注
                ->setOpenid($openId['openid'])// openId
                ->merchantPay();
            Log::info('-----商户付款-----'.json_encode($result));
        } else {
            Log::info('-----交易请求错误-----');
            Err('交易请求错误');
        }
        Log::info('-----交易成功-----');


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