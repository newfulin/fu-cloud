<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/22
 * Time: 10:31
 */
namespace Nxp\Wechat;


use Illuminate\Support\Facades\Log;

class Wechat {


    public function prePay()
    {

    }

    public function nativePay()
    {

    }

    public function jsPay()
    {

    }

    public function pay()
    {
        return app()->make(Pay::class);
    }

    public function share(){
        return app()->make(Share::class);
    }
    public function mail(){
        return app()->make(Mail::class);
    }
    public function payH5()
    {
        return app()->make(PayH5::class);
    }
    public function payApp()
    {
        return app()->make(PayApp::class);
    }
    //获取微信信息
    public function wxInfo()
    {
        return app()->make(WxInfo::class);
    }
    //红包
    public function RedPacket()
    {
        return app()->make(RedPacket::class);
    }
    //付款
    public function MerchantPay()
    {
        return app()->make(MerchantPay::class);
    }
}