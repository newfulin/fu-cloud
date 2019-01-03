<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/9
 * Time: 18:23
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommPushTempletRepo;
use App\Modules\Access\Repository\CommSmsRepo;
use App\Modules\Access\Repository\CommUserRepo;

class SmsService extends Service
{
    public $user;
    public $temp;
    public $sms;
    public $code;
    public function __construct(CommUserRepo $user,CommPushTempletRepo $temp,CommSmsRepo $sms)
    {
        $this->user = $user;
        $this->temp = $temp;
        $this->sms = $sms;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //发送短信
    public function send($request)
    {
//        $this->checkMobile($request['mobile']);  //判断手机号是否注册

        //检查同一手机号一天内短信发送次数
        $this->checkSmsNumber($request['mobile']);

        $templet = $this->getSmsTemplet($request['param']['code']);   //获取短信模版

        $smsData = config('parameter.SMS_INFO');

        $message = $this->getMessage($request,$templet['content']);

        $data = array(
            'Id'        =>$smsData['Id'],
            'Name'      =>$smsData['Name'],
            'Psw'       =>$smsData['Psw'],
            'Message'   =>$message,
            'Phone'     =>$request['mobile'],
            'Timestamp' =>'0'
        );

        $headers = array('Accept' => 'text/xml');

        $rs = \Unirest\Request::get($smsData['url'], $headers, $data);

        $rs->code;        // HTTP Status code
        $rs->headers;     // Headers
        $rs->body;        // Parsed body
        $rs->raw_body;    // Unparsed body

        if($rs->code != 200){
            Err('CAPTCHA_FILE');  //短信发送失败
        }

        $result = array();
        $rs = explode(',', $rs->body);

        foreach ($rs as $value) {
            $arr = explode(':', $value);
            $result[$arr[0]] = $arr[1];
        }

        $arr = array(
            'id'            => ID(),
            'mobile'        => $request['mobile'],
            'captcha'       => $this->code,
            'create_time'   => date('Y-m-d H:i:s'),
            'business_code' => $request['param']['code'],
        );

        $this->sms->insert($arr);

        return $result;
    }


    //推送消息  消息添加
    public function pushMessage($request)
    {
        $templet = $this->getSmsTemplet($request['param']['code']);

        $message = $this->getMessage($request['data'],$templet['content']);

        $ret['templet_id'] = $templet['templet_id'];
        $ret['message'] = $message;
        $ret['title'] = $templet['title'];

        return $ret;
    }


    //检查手机号
    public function checkMobile($mobile)
    {
        $ret = $this->user->getUserByLoginName($mobile);

        if($ret) Err('USER_MOBILE_EXIT');
    }

    /**
     * 根据模板获取消息
     */
    public function getMessage($request,$templet)
    {

        $pattern = '/[{](.*?)[}]/';
        preg_match_all($pattern,$templet,$matches);

        $res = [];
        foreach($matches[1] as $key =>$value){

            $value = trim($value);
            $funName = "get".$value;

            $res[$value]=$this->$funName($request);
        }

        foreach($res as $k=>$v){
            $pattern = '{'. $k .'}';
            $templet = str_replace($pattern,$v,$templet);
        }

        return $templet;
    }

    //获取短信模版
    public function getSmsTemplet($code)
    {
        return $this->temp->getSmsTemplet($code);
    }

    //生成随机短信
    public function getVerfiCode($request){
        $length = config('const_sms.CODELEN');
        $code = rand(pow(10,($length-1)), pow(10,$length)-1);
        $this->code = $code;
        return $code;
    }

    //过期时间
    public function getMyDate($request)
    {
        return 5;
    }

    //用户名
    public function getName($request)
    {
        $ret = $this->user->getUser($request['user_id']);
        return C($ret['user_name']);
    }

    public function getMobile($request)
    {
//        $ret = $this->user->getUserByLoginName($request['mobile']);
        return Mobile($request['mobile']);
    }

    public function getTel($request)
    {
        return $request['tel'];
    }

    public function getBookTime($request)
    {
        return $request['bookTime'];
    }

    public function getCarDesc($request)
    {
        return $request['carDesc'];
    }

    public function getCustom($request)
    {
        return $request['custom'];
    }

    public function getNumber($request)
    {
        return $request['number'];
    }


    //检查用户一天发送短信次数
    public function checkSmsNumber($mobile)
    {
        $count = $this->sms->getCountByMobile($mobile);
        $number = config('const_sms.SMS_NUMBER');

        if($count >= $number){
            Err('当前手机号短信次数发送过多,请稍后再试!');
        }

    }
}