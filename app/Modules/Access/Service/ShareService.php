<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 9:53
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Entity\AnalysisEntity;
use App\Modules\Access\Events\AnalysisEvent;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\GoodsInfoRepo;
use App\Modules\Access\Repository\TopLineRepo;
use App\Modules\Access\Repository\WeChatShareRepo;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ShareService extends Service {
	public function getRules() {
		// TODO: Implement getRules() method.
	}

	public $afterEvent = [
		AnalysisEvent::class
		=> [
			'only' => ['getShareInfo', 'getMeetShareInfo'],
		],
	];
    protected $goods;
    public function __construct(GoodsInfoRepo $goods)
    {
        $this->goods = $goods;
    }
	//获取注册分享列表
	public function getWechatShareInfo(WeChatShareRepo $repo, $request) {
		Log::info('注册分享列表 |' . $request['user_id']);
		$ret = $repo->getWechatShareInfo($request);
		return $ret;
	}

	//获取注册分享信息
	public function getShareInfo($request) {
		switch ($request['kind_of']) {
            case '10':return $this->getRegisterShareInfo($request);
            case '20':return $this->getGoodsShareInfo($request);
            case '30':return $this->getTopShareInfo($request);
            case '40':return $this->getShopShareInfo($request);
            default:return $this->getRegisterShareInfo($request);
		}
	}
    // 获取商品分享信息
    public function getGoodsShareInfo($request)
    {
        Log::info('商品分享信息 |' . $request['user_id']);

        $re = $this->goods->getShareInfo($request['id']);

        $re['logo'] = $this->getShareLogo(R($re['logo'],false));
        $re['url'] = Share(01);
        $request['re'] = $re;
        $request['open_id'] = '';
        return $request;
    }

	//获取注册分享信息
	public function getRegisterShareInfo($request) {
		Log::info('注册分享信息 |' . $request['user_id']);
		$ret = app()->make(WeChatShareRepo::class)
			->getShareInfo($request);
		$ret['logo'] = $this->getShareLogo($ret['share_little_url']);
		if ($ret) {
			$ret['url'] = Share(01);
		}
		$request['re'] = $ret;
		$request['open_id'] = '';
		return $request;
	}

	//获取头条分享数据
	public function getTopShareInfo($request) {

		Log::info('会议分享信息 |' . $request['user_id']);
		$ret = app()->make(TopLineRepo::class)
			->getShareInfo($request);

        if (!$ret) Err('分享链接不存在','7777');

		$ret['logo'] = $this->getShareLogo($ret['attr1']);
		if ($ret) {
			$ret['url'] = Share(04);
		}

		$ret['content'] = $ret['top_desc'];
		$request['re'] = $ret;
		$request['open_id'] = '';
		return $request;
	}

	//商城分享
    public function getShopShareInfo($request){
        Log::info('商城分享信息 |');
        $share = config('const_share.MALL');
        $ret['logo'] = $share['logo'];
        $ret['title'] = $share['title'];
        $ret['content'] = $share['content'];

        $ret['share_amount'] = 0;
        $ret['url'] = '';
        $request['re'] = $ret;
        $request['open_id'] = '';
        return $request;
    }

	/**
	 * 重新定义发送事件,重写 Service.eventFire
	 * @param $event
	 * @param $request
	 */
	public function eventFire($event, $request) {
		//Log::info("...ShelfProductService.eventFire...");
		Log::debug(json_encode($request));
		//$entity->setIP(getClientIP());
		if (isset($request) || isset($request[0])) {
			$entity = new AnalysisEntity();
			$entity->setId($request['id']);
			$entity->setIP($request['ip']);
			$entity->setName($request['re']['title']);
			$entity->setDesc($request['re']['content']);
			$entity->setType($request['kind_of']);
			$entity->setUserId($request['user_id']);
			$entity->setRemark($request['remark']);
			$entity->setOpenId($request['open_id']);
			Log::debug(json_encode($entity));

			Event::fire(new AnalysisEvent($entity));
		}
	}

	public function getShareLogo($url) {

        $before = $result = substr($url,0,strrpos($url,"/")) . '/';
        $after = trim(strrchr($url, '/'),'/');

        if($url){
            $thumb_url = $before . 'thumb_' . $after;
            if($this->img_exits($thumb_url)){
                return $thumb_url;
            }
        }
        return R('webimg/logo/miller.png');
	}

	//二次分享数据
	public function webShare(CommCodeMasterRepo $repo,$request) {
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$noncestr = $this->getRandString();
		// 检测文件是否存在
		$check = file_exists(config('parameter.SHARE.root'));
		if ($check == false) {
			$reToken = $this->getToken();

			$jsapi_ticket = $reToken['ticket'];
			$access_token = $reToken['access_token'];
			$json = array(
				'expire_time' => $timestamp,
				'jsapi_ticket' => $jsapi_ticket,
				'access_token' => $reToken['access_token'],
			);
			Log::info('缓存Token文件不存在，生成新文件' . json_encode($json));
			$this->fileWrite(json_encode($json));
		}
		$json = json_decode($this->fileRead(), true);

		if ($timestamp < $json['expire_time'] + 7000) {
			$json = $this->fileRead();
			Log::info('缓存有效' . $json);
			$json = json_decode($json, true);
			$access_token = $json['access_token'];
		} else {
			$reToken = $this->getToken();
			$jsapi_ticket = $reToken['ticket'];
			$access_token = $reToken['access_token'];
			$json = array(
				'expire_time' => $timestamp,
				'jsapi_ticket' => $jsapi_ticket,
				'access_token' => $reToken['access_token'],
			);
			Log::info('缓存Token已过期，重新请求Token，新旧Token五分钟内同时有效' . json_encode($json));
			$this->fileWrite(json_encode($json));
		}
		$arr = array(
			'noncestr' => $noncestr,
			'jsapi_ticket' => $json['jsapi_ticket'],
			'timestamp' => $timestamp,
			'url' => $request['url'],
		);
		Log::debug('makeSign' . json_encode($arr));
		//从配置数据中获取而日新配置
        $master = $repo->getConfigure('wxconfig_public','wx');

		$ret = array(
			'appId' => $master['property2'],
			'timestamp' => $timestamp,
			'nonceStr' => $noncestr,
			'signature' => $this->makeSign($arr),
		);
		return $ret;
	}

	/**
	 * 生成签名
	 * @param $params
	 * @return string 签名
	 */
	public function makeSign($params) {
		//签名步骤一：按字典序排序数组参数
		ksort($params);
		$string = $this->ToUrlParams($params);
		Log::debug('$string========' . $string);
		//签名步骤二：对string1进行sha1签名，得到signature
		$signature = sha1($string);
		return $signature;
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

	/**
	 * 产生一个指定长度的随机字符串
	 * @param int $len 产生字符串的长度
	 * @return string 随机字符串
	 */
	public function getRandString($len = 32) {
		$chars = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
			"3", "4", "5", "6", "7", "8", "9",
		);

		$charsLen = count($chars) - 1;
		// 将数组打乱
		shuffle($chars);
		$ret = "";
		for ($i = 0; $i < $len; $i++) {
			$ret .= $chars[mt_rand(0, $charsLen)];
		}
		Log::debug('$ret' . $ret);
		return $ret;
	}

	public function fileWrite($json) {
		$file = fopen(config('parameter.SHARE.root'), "w");
		fwrite($file, $json);
		fclose($file);
		return $json;
	}

	public function fileRead() {
		$file = fopen(config('parameter.SHARE.root'), "r");
		$json = fread($file, filesize(config('parameter.SHARE.root')));
		fclose($file);
		return $json;
	}

	/**
	 * 获取Token
	 * @return mixed
	 */
	public function getToken() {
		$urlToken = config('parameter.SHARE.tokenUrl') . '&appid=' . config('parameter.SHARE.appId') . '&secret=' . config('parameter.SHARE.AppSecret');
		Log::debug('$urlToken===' . $urlToken);
		$ret_token = $this->httpGet($urlToken);
		$access_token = $ret_token['access_token'];
		$urlTicket = config('parameter.SHARE.ticketUrl') . $access_token;
		Log::debug('$urlTicket=====' . $urlTicket);
		$ret_ticket = $this->httpGet($urlTicket);
		$re['access_token'] = $access_token;
		$re['ticket'] = $ret_ticket['ticket'];
		return $re;
	}

	/**
	 * GET请求
	 * @param $url
	 * @return mixed
	 */
	public function httpGet($url) {
		// 1. 初始化
		$curl = curl_init();
		// 2. 设置选项，包括URL
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		// 3. 执行并获取HTML文档内容
		$ret = curl_exec($curl);
		if ($ret === FALSE) {
			Err('请求失败，请重试', '7777');
		}
		// 4. 释放curl句柄
		curl_close($curl);
		Log::info('Token' . $ret);
		return json_decode($ret, true);
	}

    /**
     * @param 判断远程图片是否存在
     */
    public function img_exits($url){
        $header  = @get_headers($url, true);

        if(isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'))){
            return true;
        }else{
            return false;
        }
    }
}