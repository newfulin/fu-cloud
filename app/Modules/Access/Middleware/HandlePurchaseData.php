<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 16:11
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\MaterialUploadRepo;
use App\Modules\Access\Repository\PurchaseOrderRepo;
use Closure;

class HandlePurchaseData extends Middleware
{
    public function __construct(MaterialUploadRepo $repo,PurchaseOrderRepo $purchase)
    {
        $this->repo = $repo;
        $this->purchase = $purchase;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $order = $response['id'];
        unset($response['id']);
        $num = 0;
        $ret = [];

        $purchaseInfo = $this->purchase->getMaritalStatus($order);
        if($purchaseInfo['marital_status'] == '01'){
            unset($response['spouse_opposite']);
            unset($response['spouse_front']);
            unset($response['authorize_spouse']);
        }

        foreach($response as $key => $val){
            $ret[$num]['name'] = $key;
            $ret[$num]['value'] = $val;
            //图片地址
            $path = $this->repo->getImgUrl($order,$key);

            $ret[$num]['path'] = $path;
            $num +=1;
        }

        return $ret;
    }
}