<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/18
 * Time: 09:40
 */
namespace App\Middleware;

use Closure;


//后置中间件
class Response {

    public function handle($request, Closure $next)
    {
        //这里体现出了后置中间件
        $response =  $next($request)->original;
        if(is_array($response) && isset($response['exception'])){
            $code = $response['exception']['code']>1000 ? 200 : $response['exception']['code'] ;
            $ret = [
                'ret' =>$code,
                'data'=>[],
                'code' => $response['exception']['code'],
                'message' =>$response['exception']['msg']
            ];
        }else{
            $ret = [
                'ret' =>200,
                'data'=>$response,
                'code' =>'0000',
                'message' =>'请求成功'
            ];
        }
        return response()->json($ret);
    }
}