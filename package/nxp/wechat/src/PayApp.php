<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/22
 * Time: 11:44
 */


namespace Nxp\Wechat;

use App\Common\Models\WxPayOrder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

require_once __DIR__ . "/../WxSDK/WxPay.Api.php";
require_once __DIR__ . "/../WxSDK/WxPay.NativePay.php";


class PayApp
{

    public $input;
    public $notify;


    public function __construct()
    {
        $this->input = new \WxPayUnifiedOrder();
        $this->notify = new \NativePay();
    }

//  公共账号
    public function setAppid()
    {
        $appid = config('parameter.WE_CHAT.appId');
        $this->input->SetAppid($appid);
        return $this;
    }

//  商户号
    public function setMchId()
    {
        $mch_id = config('parameter.WE_CHAT.mchId');
        $this->input->SetMch_id($mch_id);
        return $this;
    }

//  签名
    public function setSign()
    {
        $this->input->SetSign();

        return $this;
    }


//  商品描述
    public function setBody($body)
    {
        $this->input->SetBody($body);
        return $this;
    }

//  商品订单号
    public function setOutTradeNo()
    {
        $out_trade_no = ID();
        $this->input->SetOut_trade_no($out_trade_no);

        return $this;
    }

//  标价金额
    public function setTotalFee($total_fee)
    {
        $this->input->SetTotal_fee($total_fee);
        return $this;
    }

//  终端IP
    public function setSpbillCreateIp()
    {
        if ($_SERVER['REMOTE_ADDR'] == '::1') {
            $this->input->SetSpbill_create_ip("127.0.0.1");
        } else {
            $this->input->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);
        }
        return $this;
    }

//  通知地址
    public function setNotifyUrl($notify_url)
    {
        $this->input->SetNotify_url($notify_url);
        return $this;
    }

//  交易类型
    public function setTradeType()
    {
        $trade_type = $appid = config('parameter.WE_CHAT.tradeType');
        $this->input->SetTrade_type($trade_type);
        return $this;
    }

//  商品ID
    public function setProductId()
    {
        $product_id = rand(10000, 19999);
        $this->input->SetProduct_id($product_id);
        return $this;
    }

//  交易起始时间
    public function setTimeStart()
    {
        $this->input->SetTime_start(date("YmdHis"));
        return $this;
    }

//  设置订单结束时间   二维码有效期
    public function setTimeExpire()
    {
        $this->input->SetTime_expire(date("YmdHis", time() + 600));
        return $this;
    }
    // 设置商户数据包
    public function setAttach($attach)
    {
        $this->input->SetAttach($attach);
        return $this;
    }
//  统一下单
    public function getOrder()
    {
        $obj = $this->input;
        $re = $this->notify->GetPayUrl($this->input);
        // 订单生成失败
        if ($re['return_code'] != 'SUCCESS')
        {
            Err($re['return_msg']);
        }
        if ($re['result_code'] != 'SUCCESS')
        {
            Err($re['err_code_des']);
        }
        // 返回结果处理
        $result = array(
            'appid' => $re['appid'],
            'partnerid' => $re['mch_id'],
            'noncestr' => $re['nonce_str'],
            'prepayid' => $re['prepay_id'],
            'timestamp' => time(),
            'package' => 'Sign=WXPay',
            'sign' => $obj->MakeSign(),
            'out_trade_no' => $obj->GetOut_trade_no(),
            'body'  =>  $obj->GetBody(),
            'spbill_create_ip'  => $obj->GetSpbill_create_ip(),
            'total_fee' =>  $obj->GetTotal_fee(),
            'code_url'  => $re['code_url'],
        );
        return $result;
    }

}
