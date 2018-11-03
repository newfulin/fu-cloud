<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/2
 * Time: 16:03
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommBankDbRepo;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\CommSupportBankInfoRepo;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\SysCityRepo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CommonService extends Service
{
    public function getRules(){

    }

    //根据城市,银行  获取银联列表
    public function getAreaBankList(CommBankDbRepo $repo,$request)
    {
        return $repo->getAreaBankList($request);
    }

    //获取银行接口
    public function getSupportBankList(CommSupportBankInfoRepo $repo)
    {
        return $repo->getSupportBankList();
    }

    //查询省
    public function getProvinceList(SysCityRepo $repo)
    {
        return $repo->getProvinceList();
    }

    //查询市
    public function getCityList(SysCityRepo $repo,$request)
    {
        return $repo->getCityListByProvinceId($request['provinceId']);
    }

    //获取联系我们 信息  电话
    public function getContactInfo()
    {
        $ret['tel'] = config('const_param.TEL.code');
        return $ret;
    }

    //特殊符号 emoji表情转义
    public function userTextEncode($request){
        $str = $request['str'];
        if(!is_string($str))return $str;
        if(!$str || $str == 'undefined')return '';

        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }

    //微信 特殊昵称处理 emoji 处理
    public function userTextDecode($request){
        $str = $request['str'];
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }

    //获取微信配置信息
    public function getWxConfigInfo(CommCodeMasterRepo $repo){
        $ret =  $repo->getConfigure('wxconfig_public','wx');
        $arr['appid'] = $ret['property2'];
        $arr['appsecret'] = $ret['property3'];
        return $arr;
    }

    //二维码生成
    public function getQrcode(CommUserRepo $user,$request){
        $userInfo = $user->getUser($request['user_id']);
        $logo = $userInfo['headimgurl'];

        if($request['url']){
            $url = $request['url'].'?userId='.$request['user_id'];
        }else{
            $url = 'http://mall.melenet.com'.'?userId='.$request['user_id'];
        }




        $dir = 'Data/upload/qrcode/'.'qrcode_'.$request['user_id'].'.png';
        if(!file_exists("$dir")){
//            QrCode::format('png')->size('180')->merge($logo,.22)->margin(0)->generate($url,$dir); //保存路径);
            QrCode::format('png')->size('200')->margin(0)->generate($url,$dir); //保存路径);
        }
        $data['qr_code'] = R(substr($dir,5));
        $data['title'] = '美乐精选';
//        getMyPromotionCount
        $param = [
            'user_id' => $request['user_id']
        ];

        $number = app('nxp-team')->query()
            ->getMyPromotionCount($param);
        $data['number'] = $number;

        return $data;
    }
}