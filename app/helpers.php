<?php

use App\Common\Contracts\Repository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use QL\QueryList;

if(! function_exists('Err')){
    function Err($message , $code=9999){

        if(Str::contains($message,":")){
            list($message,$code) =explode(':',$message);
        }else{
            $code = config('const_response.'.$message.'.code',$code);
            $message = config('const_response.'.$message.'.msg',$message);
        }
        if(is_array($message) || is_object($message)){
            $message = json_encode($message);
        }

        if(DB::transactionLevel()){
            DB::rollback();
        }
        throw  new Exception($message , $code);
    }
}

if(! function_exists('ID')){
    function ID (){
        return app()->make(\App\Common\Helpers\IdWorker::class)->getId();
    }
}


if(! function_exists('Token')){

    function Token(){
        return app()->make(\App\Common\Jwt\Token::class);
    }

}

if(! function_exists('Money')){

    function Money(){
        return app()->make(\App\Common\Helpers\Money::class);
    }

}

if(! function_exists('Repo')){

    function Repo($repository){
        return app()->make($repository);
    }

}

if(! function_exists('DICode')){
    function DICode ($confile, $param){
        $confile = 'const_'.$confile ;
        $code = \Illuminate\Support\Facades\Config::get($confile.'.'.$param .'.code');
        $code = $code!=null ? $code : '9999';
        return $code ;
    }
}

if(! function_exists('DIMsg')){
    function DIMsg ($confile, $param){
        $confile = 'const_'.$confile ;
        $msg = \Illuminate\Support\Facades\Config::get($confile.'.'.$param .'.msg');
        $msg = $msg ? $msg : '未知错误';
        return $msg ;
    }
}

//资源路径
if(! function_exists('R')) {
    function R($path = null,$flag = true)
    {
        if(app()->runningInConsole()){
            return null;
        }
        if($flag){
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/Data" . "/";
        }else{
            $url = "http://mallpms.melenet.com/image/";
        }
        
        if (is_array($path)) {
            $src = array();
            foreach ($path as $key => $val) {
                $src[$key] = $url . $val;
            }
            return $src;
        }

        return $url . $path;
    }
}

/** 名字*处理 */

if(! function_exists('C')){
    function C($str){
        $length = mb_strlen($str,'UTF8');
        if($length<=0)  return '*';

        $first = mb_substr($str,0,1,'utf-8') . '*';
        $last  = '';
        if($length >= 3) {
            $last  = mb_substr($str, -1, 1,'utf-8');
        }

        return $first . $last;
    }
}

/* 手机号 * 处理 */
if(! function_exists('Mobile')){
    function Mobile($value){
        $prefix = substr($value,0,3);
        //截取身份证号后4位
        $suffix = substr($value,-4,4);

        return $prefix."****".$suffix;
    }
}
/**
 * 获取客户端IP
 */
if(! function_exists('getClientIP')){
    function getClientIP(){
        //判断服务器是否允许$_SERVER
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
}
/**
 * 分享链结URL设定
 */
if (! function_exists('Share'))
{
    function Share($kind_of){
        switch ($kind_of){
            case '01' : return config('const_share.URL.goods');
            // case '01' : return config('const_share.URL.app');
            case '02' : return config('const_share.URL.meeting');
            case '03' : return config('const_share.URL.cafe');
            case '04' : return config('const_share.URL.top');
            case '05' : return config('const_share.URL.sign_up');
        }
    }
}

if (! function_exists('RUeditor')){
    function RUeditor($path = null)
    {
        if(app()->runningInConsole()){
            return null;
        }
        $url = "http://192.168.1.2:8096";

        return $url . $path;
    }
}


/**
 * html转换
 */
if (!function_exists('makeJsContent')){
    function makeJsContent($html)
    {
        $rules = [
            'text' => ['span', 'text'],
            'text_style' => ['span', 'style'],
            'img' => ['img', 'src'],
            'img_w' => ['img', 'width'],
            'img_h' => ['img', 'height'],

        ];
        $data = QueryList::html($html)
            ->rules($rules)
            ->range('p')
            ->query()
            ->getData(function ($item) {
                if ($item['img']) {
                    return [
                        'name' => 'ViewImg',
                        'src' => RUeditor($item['img'],false),
                        'height' => $item['img_h'],
                        'width' => $item['img_w']
                    ];
                } elseif ($item['text']) {
                    $item['name'] = 'ViewText';
                    foreach (explode(';', $item['text_style']) as $style) {
                        $exp = explode(':', $style);
                        if (count($exp) > 1) {
                            list($k, $v) = $exp;
                            $item['style'][trim(str_replace('-', '_', $k))] = $v;
                        }

                    }

                    return [
                        'name' => 'ViewText',
                        'content' => $item['text'],
                        'style' => $item['style']
                    ];
                } else {
                    return [
                    ];
                }

            });
        return $data->all();
    }
}
/**
 * @desc 获取数据字典
 */
if (! function_exists('getConfigure'))
{
    function getConfigure($code,$key){
        $ret = app(\App\Modules\Access\Repository\CommCodeMasterRepo::class)
            ->getConfigure($code,$key);
        $ret['code'] = $code;
        $ret['key'] = $key;
        return $ret;
    }
}


