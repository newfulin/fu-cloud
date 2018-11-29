<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/30
 * Time: 11:08
 */

namespace App\Modules\Headline\Service;


use App\Common\Contracts\Service;
use App\Modules\Headline\Repository\TopLineRepo;
use App\Modules\Headline\Repository\WeChatShareRepository;
use Illuminate\Support\Facades\Log;

class ShareService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //获取头条分享
    public function getTopShare($request)
    {
        Log::debug('request'.json_encode($request));

        $ret = app()->make(TopLineRepo::class)
            ->getShareInfo($request);
        $ret['logo'] = $this->getShareLogo($ret['attr1']);
        if($ret){
            $ret['url'] = Share(04);
        }
        $ret['content'] = $ret['top_desc'];
        $request['re'] = $ret;
        return $request;
    }

    //获取Web端页面二次分享的信息
    public function webShare($request)
    {

        $timestamp = time();
        $noncestr = $this->getRandString();
        //检测文件是否存在
        $check = file_exists(config('parameter.SHARE.root'));
        if($check == false)
        {
            $reToken = $this->getToken();
            $jsapi_ticket = $reToken['ticket'];
            $access_token = $reToken['access_token'];
            $json = array(
                'expire_time' => $timestamp,
                'jsapi_ticket' => $jsapi_ticket,
                'access_token' => $access_token,
            );

            Log::info("缓存Token文件不存在，生成新文件".json_encode($json));
            $this->fileWrite(json_encode($json));
        }

        $json = json_decode($this->fileRead(),true);


        if ($timestamp < $json['expire_time'] + 7000 ) {

            $json = $this->fileRead();
            Log::info('缓存有效'.$json);
            $json = json_decode($json,true);

        } else {
            $reToken = $this->getToken();
//            exit;
            $jsapi_ticket = $reToken['ticket'];
            $json = array(
                'expire_time' => $timestamp,
                'jsapi_ticket' => $jsapi_ticket,
                'access_token' => $reToken['access_token'],
            );
            Log::info('缓存Token已过期，重新请求Token，新旧Token五分钟内同时有效'.json_encode($json));
            $this->fileWrite(json_encode($json));
        }
        $arr = array(
            'timestamp' => $timestamp,
            'jsapi_ticket' => $json['jsapi_ticket'],
            'url' => $request['url'],
            'noncestr' => $noncestr,
        );
        Log::info('makeSign ------dangqina --------     '.json_encode($arr));
        $ret = array(
            'appId' => config('parameter.SHARE.appId'),
            'timestamp' => $timestamp,
            'nonceStr' => $noncestr,
            'signature' => $this->makeSign($arr),
//            'access_token' => $access_token,
        );

        return $ret;
    }

    /**
     * 产生一个指定长度的随机字符串
     * @param  int  $length 产生字符串的长度
     * @return string 随机字符串
     */
    public function getRandString($len = 32)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );

        $charLen = count($chars) - 1;
        //将数组打乱
        shuffle($chars);
        $ret = "";
        for($i=0; $i<$len; $i++){
            $ret .= $chars[mt_rand(0, $charLen)];
        }

        Log::debug('$ret:|' .$ret);
        return $ret;
    }

    /**
     * @获取token
     * @return mixed
     */
    public function getToken()
    {
        $urlToken = config('parameter.SHARE.tokenUrl') . '&appId=' .config('parameter.SHARE.appId') . '&secret=' . config('parameter.SHARE.AppSecret');
        Log::debug('$urlToken =======' . $urlToken);
        $ret_token = $this->httpGet($urlToken);

        $access_token = $ret_token['access_token'];
        $urlTicket = config('parameter.SHARE.ticketUrl') . $access_token;
        Log::debug('$urlTicket====='. $urlTicket);
        $ret_ticket = $this->httpGet($urlTicket);
        $re['access_token'] = $access_token;
        $re['ticket'] = $ret_ticket['ticket'];
        return $re;
    }

    /**
     * get 请求
     * @param $url
     * @return mixed
     */
    public function httpGet($url)
    {
        // 1. 初始化
        $curl = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($curl,CURLOPT_TIMEOUT, 500);
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER,0);
        // 3. 执行并获取HTML文档内容
        $ret = curl_exec($curl);
        Log::info('执行并获取HTML文档内容'.$ret);
        if($ret === FALSE ){
            Err('请求失败，请重试','7777');
        }
        // 4. 释放curl句柄
        curl_close($curl);
        Log::info('Token'.$ret);
        return json_decode($ret,true);
    }

    public function fileWrite($json)
    {
        $file = fopen(config('parameter.SHARE.root'),'w');
        fwrite($file,$json);
        fclose($file);
        return $json;
    }

    public function fileRead()
    {
        $file = fopen(config('parameter.SHARE.root'),'r');
        $json = fread($file,fileSize(config('parameter.SHARE.root')));
        fclose($file);
        return $json;
    }

    /**
     * 生成签名
     * @param $params
     * @return string 签名
     */
    public function makeSign( $params ){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        Log::debug('$string========'.$string);
        //签名步骤二：对string1进行sha1签名，得到signature
        $signature = sha1($string);
        return $signature;
    }
    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;

    }


    public function getShareLogo($url)
    {
        return !empty($url) ? $url : R('webimg/logo/coffee.png');
    }

}