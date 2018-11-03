<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 14:05
 */

namespace Nxp\Wechat;

use Illuminate\Support\Facades\Log;

require_once __DIR__ . "/../WxSDK/WxPay.JsApiPay.php";

class WxInfo
{

    public function __construct()
    {
        $this->wx = new \JsApiPay();
    }

    //获取微信信息
    public function getWxInfo($request){
        return $this->wx->GetWxInfo($request['openid'],$request['access_token']);
    }

    //获取openid
    public function getOpenId($code,$flag = true)
    {
        $openid = $this->wx->GetOpenid('',$code,$flag);
        Log::info('openidId============'.json_encode($openid));
        return $openid;
    }
}