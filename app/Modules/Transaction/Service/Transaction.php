<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 16:59
 */

namespace App\Modules\Transaction\Service;

use App\Common\Contracts\Service;
use App\Modules\Transaction\Middleware\DetailMiddleware;
use App\Modules\Transaction\Middleware\SummaryMiddleware;
use App\Modules\Transaction\Middleware\RouteMiddleware;
use Illuminate\Support\Facades\Config;

class Transaction extends Service{


    public $middleware = [
        RouteMiddleware::class,
        SummaryMiddleware::class,
        DetailMiddleware::class
    ];

    public $beforeEvent =[

    ];

    public $afterEvent = [

    ];


   public function getRules()
   {
        return [
            'handle' =>[
                'businessCode' =>'required',
                'tariffCode' =>'required',
                'processId' =>'required',
                'userId'   =>'required',
                'transAmt' =>'required'
            ]
        ];
   }

    public function handle($request)
    {
        //这处理返回的报文
        return $request;
    }

    public function getConfigMiddleware($method)
    {
        $config = 'transaction.business.'.$this->request['businessCode'];
        return Config::get($config);
    }
}