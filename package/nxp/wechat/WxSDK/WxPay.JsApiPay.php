<?php
require_once "WxPay.Api.php";
use App\Modules\Access\Repository\CommCodeMasterRepo;

/**
 * 
 * JSAPI支付实现类
 * 该类实现了从微信公众平台获取code、通过code获取openid和access_token、
 * 生成jsapi支付js接口所需的参数、生成获取共享收货地址所需的参数
 * 
 * 该类是微信支付提供的样例程序，商户可根据自己的需求修改，或者使用lib中的api自行开发
 * 
 * @author widy
 *
 */
class JsApiPay
{
	/**
	 * 
	 * 网页授权接口微信服务器返回的数据，返回样例如下
	 * {
	 *  "access_token":"ACCESS_TOKEN",
	 *  "expires_in":7200,
	 *  "refresh_token":"REFRESH_TOKEN",
	 *  "openid":"OPENID",
	 *  "scope":"SCOPE",
	 *  "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
	 * }
	 * 其中access_token可用于获取共享收货地址
	 * openid是微信支付jsapi支付接口必须的参数
	 * @var array
	 */
	public $data = null;

    /**
	 * 
	 * 通过跳转获取用户的openid，跳转流程如下：
	 * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
	 * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
	 * 
	 * @return 用户的openid
	 */
	public function GetOpenid($baseUrl = null,$code = null,$flag)
	{
		//通过code获得openid
		if (empty($code)){
				//触发微信返回code码
				$url = $this->__CreateOauthUrlForCode($baseUrl);
				
				Header("Location: $url");
		} else {
			//获取code码，以获取openid
			$openid = $this->getOpenidFromMp($code,$flag);
			return $openid;
		}
	}
	
	/**
	 * 
	 * 获取jsapi支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @throws WxPayException
	 * 
	 * @return json数据，可直接填入js函数作为参数
	 */
	public function GetJsApiParameters($UnifiedOrderResult)
	{
		if(!array_key_exists("appid", $UnifiedOrderResult)
		|| !array_key_exists("prepay_id", $UnifiedOrderResult)
		|| $UnifiedOrderResult['prepay_id'] == "")
		{
			throw new WxPayException("参数错误");
		}
		$jsapi = new WxPayJsApiPay();
		$jsapi->SetAppid($UnifiedOrderResult["appid"]);
		$timeStamp = time();
		$jsapi->SetTimeStamp("$timeStamp");
		$jsapi->SetNonceStr(WxPayApi::getNonceStr());
		$jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
		$jsapi->SetSignType("MD5");
		$jsapi->SetPaySign($jsapi->MakeSign());
		$parameters = json_encode($jsapi->GetValues());
		return $parameters;
	}
	
	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function GetOpenidFromMp($code,$flag)
	{
		$url = $this->__CreateOauthUrlForOpenid($code,$flag);
		return $this->ToCurl($url);
	}
	
	/**
	 * 
	 * 通过openid,access_token从工作平台获取用户信息
	 * @param string $openid 微信跳转回来带上的openid
	 * @param string $access_token 微信跳转回来带上的access_token
	 * 
	 * @return openid
	 */
	public function GetWxInfo($openid,$access_token)
	{
		$url = $this->__CreateOauthUrlForWxInfo($openid,$access_token);

		return $this->ToCurl($url);
	}

	/**
	 * 
	 * 拼接签名字符串
	 * @param array $urlObj
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 
	 * 获取地址js参数
	 * 
	 * @return 获取共享收货地址js函数需要的参数，json格式可以直接做参数使用
	 */
	public function GetEditAddressParameters()
	{	
		$getData = $this->data;
		$data = array();
		$data["appid"] = WxPayConfig::APPID;
		$data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$time = time();
		$data["timestamp"] = "$time";
		$data["noncestr"] = "1234568";
		$data["accesstoken"] = $getData["access_token"];
		ksort($data);
		$params = $this->ToUrlParams($data);
		$addrSign = sha1($params);
		
		$afterData = array(
			"addrSign" => $addrSign,
			"signType" => "sha1",
			"scope" => "jsapi_address",
			"appId" => WxPayConfig::APPID,
			"timeStamp" => $data["timestamp"],
			"nonceStr" => $data["noncestr"]
		);
		$parameters = json_encode($afterData);
		return $parameters;
	}

	/**
	 * 
	 * 构造获取code的url连接
	 * @param string $redirectUrl 微信服务器回跳的url，需要url编码
	 * 
	 * @return 返回构造好的url
	 */
	private function __CreateOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = WxPayConfig::APPID;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = 'STATE';
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	/**
	 * 
	 * 构造获取open和access_toke的url地址
	 * @param string $code，微信跳转带回的code
	 * 
	 * @return 请求的url
	 */
	private function __CreateOauthUrlForOpenid($code,$flag)
	{
//		$urlObj["appid"] = WxPayConfig::APPID;
//		$urlObj["secret"] = WxPayConfig::APPSECRET;
		if($flag == 'true'){
            $ret = app()->make(CommCodeMasterRepo::class)->getConfigure('wxconfig_open_platform','wx');
            $urlObj["appid"] = $ret['property2'];
            $urlObj["secret"] = $ret['property3'];
        }else if ($flag == 'program'){
            $ret = app()->make(CommCodeMasterRepo::class)->getConfigure('wxconfig_program','wx');
            $urlObj["appid"] = $ret['property2'];
            $urlObj["secret"] = $ret['property3'];
            $urlObj["js_code"] = $code;
            $urlObj["grant_type"] = "authorization_code";
            \Illuminate\Support\Facades\Log::info('program----------------'.json_encode($urlObj));
            $bizString = $this->ToUrlParams($urlObj);
            \Illuminate\Support\Facades\Log::info('string----------------'.$bizString);

            return "https://api.weixin.qq.com/sns/jscode2session?".$bizString;
        }else{
            $ret = app()->make(CommCodeMasterRepo::class)->getConfigure('wxconfig_public','wx');
            $urlObj["appid"] = $ret['property2'];
            $urlObj["secret"] = $ret['property3'];
        }
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}

	/**
	 * 
	 * 构造获取微信信息
	 * @param string $openid
	 * @param string $access_token
	 * 
	 * @return 请求的url
	 */
	private function __CreateOauthUrlForWxInfo($openid,$access_token)
	{
		$urlObj["access_token"] = $access_token;
		$urlObj["openid"] = $openid;
		$urlObj["lang"] = 'zh_CN';
		
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
	}

	/**
	 * curl url  执行
	 */
	public function ToCurl($url)
	{
		$ch = curl_init();
		//设置超时
		// curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
			&& WxPayConfig::CURL_PROXY_PORT != 0){
			curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
			curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
		}
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;
	}
}