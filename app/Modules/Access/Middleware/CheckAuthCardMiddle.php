<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/29
 * Time: 9:01
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class CheckAuthCardMiddle extends Middleware
{
    public function handle($request, Closure $next)
    {
        $api = "/merc/auth";
        $data = [
            'accountNo' => $request['accountNo'],
            'accountName' =>$request['accountName'],
            'bankLeaveMobile' => $request['bankLeaveMobile'],
            'idNo' => $request['idNo']
        ];
        $ret = $this->sendRequest($api,$data);

        $ret = get_object_vars($ret);

        if($ret['body']->code != '0000'){
            Err($ret['body']->msg);
        }

        return $next($request);

    }

    public function sendRequest($api,$data)
    {

        $secret  = config('agent.SECRET.code');
        $agentId = config('agent.AGENTID.code');
        $host    = config('agent.HOST.code');

        $jsonData = json_encode($data);

        $joinStr = $jsonData.$secret;
        $signature = md5($joinStr);
        $encryptData = base64_encode($jsonData);
        $url = $host . $api ;
        $params = "agentId=".$agentId;
        $params .="&encryptData=".$encryptData;
        $params .="&signature=".$signature;

        $response = \Unirest\Request::post($url, '', $params);
        return $response;
    }
}