<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/2
 * Time: 14:44
 */
namespace App\Modules\Transaction\Service;

use App\Common\Contracts\Service;
use App\Modules\Finance\Middleware\UtilDictMiddle;
use App\Modules\Transaction\Repository\TranTransOrderRepo;

class TranTransOrderService extends Service{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        UtilDictMiddle::class => [
            'only' => ['getListTransOrder']
        ]
    ];

    /*查询流水*/
    public function getListTransOrder(TranTransOrderRepo $repo,$request)
    {
        $ret = $repo->getTransOrderByMercId($request);
        foreach($ret as $key => $val){
            $ret[$key]->type_img = config('common.trans_order_status_img.'.$val->business_code);
        }

        $request['dict'] = 'dict.tran_trans_order';
        $request['list'] = $ret;

        return $request;
    }
}