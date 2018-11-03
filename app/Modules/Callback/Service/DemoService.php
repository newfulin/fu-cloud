<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:19
 */
namespace  App\Modules\Callback\Service ;


use App\Common\Contracts\Service;
use App\Modules\Callback\Events\DemoServiceBeforeEvent;
use App\Modules\Callback\Middleware\OneMiddle;
use App\Modules\Callback\Middleware\TwoMiddle;
use Illuminate\Support\Facades\Config;

class DemoService extends Service {


    public $middleware = [
        OneMiddle::class,
        TwoMiddle::class
    ];
    public $beforeEvent = [
        DemoServiceBeforeEvent::class
    ];
    public $afterEvent = [

    ];



    public function getRules()
    {
       return [];
    }





    public function handle($request)
    {
       return $request;
    }


    public function getConfigMiddleware($method)
    {
        return Config::get('callback.middle');

    }



}