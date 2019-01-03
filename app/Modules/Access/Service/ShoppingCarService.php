<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/12/21
 * Time: 18:41
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\ShoppingCarRepo;

class ShoppingCarService extends Service
{

    public $repo;

    public function __construct(ShoppingCarRepo $repo)
    {
        $this->repo = $repo;
    }

    public function getRules(){
        // todo
    }

    // 检测商品库存
    public $middleware = [

    ];

    public function addGoodsToCar($request){
        return $this->repo->addGoodsToCar($request);
    }

    public function getMyGoodsCar($request){
        return $this->repo->getMyGoodsCar($request['user_id']);
    }

    public function updateGoodsCar($request){
        return $this->repo->updateGoodsCar($request);
    }

    public function delGoodsCar($request){
        return $this->repo->delGoodsCar($request);
    }
}