<?php

namespace App\Modules\Access\Middleware;

use App\Common\Contracts\Middleware;
use \Closure;

class ShareMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
//        $request['name'] = '我是handle````';
        $uuid = '123456789';
        $request = array(
            'uuid' => '123456789',
            'code' => 'asd',
            'user_name' => '游客' . substr($uuid,-5),
            'agent_id'  => '100010000000000',
        );
        return $next($request);
    }
//    public function register($request, Closure $next)
//    {
//        $request['name'] = '我是register';
//        return $next($request);
//    }
//    public function login($request, Closure $next)
//    {
//        $request['name'] = '我是login';
//        return $next($request);
//    }
}