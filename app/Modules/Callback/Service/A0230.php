<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 8:55
 */

namespace App\Modules\Callback\Service;


use App\Common\Contracts\Service;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\Log;

class A0230 extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //更新流水
    public function update(TranTransOrderRepo $repo,$request){
        Log::info(' pms升级 | '.$request['detailId']);
        return $repo->update($request['detailId'],$request['params']);
    }
}