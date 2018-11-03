<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 08:50
 */
namespace App\Modules\Finance\Service ;

use App\Common\Contracts\Service;

class TariffRateService extends Service {

    /**
     * @return mixed
     */
    public function getRules()
    {
        return [];
    }


    /**
     * 计算到账额度
     */
    public function getReceiveAmount()
    {
        
    }

}