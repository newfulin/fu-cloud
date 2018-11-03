<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 8:45
 */

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use App\Modules\Transaction\Repository\WithdrawOrderRepo;
use Illuminate\Support\Facades\Log;

class A0700 extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //更新流水
    public function update(WithdrawOrderRepo $repo,$request){
        Log::info('更新用户提现表 | '.$request['detailId']);
        return $repo->update($request['detailId'],$request['params']);
    }
}