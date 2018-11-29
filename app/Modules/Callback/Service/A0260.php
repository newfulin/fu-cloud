<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21
 * Time: 10:56
 */

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use Illuminate\Support\Facades\Log;

class A0260 extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function update(){
        Log::info('积分转赠');
        return ;
    }
}