<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:07
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Middleware\WxxCxLoginMiddle;

class WxxCxLoginService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        WxxCxLoginMiddle::class
    ];

    public function handle($request){
        return $request;
    }
}