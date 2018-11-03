<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 09:13
 */
namespace App\Modules\Transaction\Middleware ;


use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Channel\ChannelHandle;
use Closure;
use Illuminate\Support\Facades\Config;

class ChannelTransaction extends Middleware {
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $channel = Config::get('transaction.channel.'.$request['route']);
        //采用注入的方式
//        $request['channel'] = (new ChannelHandle(app($channel)))->handle($request);
        $request['channel'] = app($channel)->handle($request);

        return $next($request);

    }


}