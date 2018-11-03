<?php
/**
 * 用户各种金额计算服务类
 */
namespace App\Modules\Finance\Service;

use App\Modules\Finance\Repository\AcctUserTariffRateRepository;




class MercTariffRate
{

    public $repository;

    /**
     * 注入Repository
     */
    public function  __construct(AcctUserTariffRateRepository $Repository){
         $this->repository = $Repository;
    }
    
    /**
     * 得到资费列表
     */
    public function getRateInfo($businessCode, $mercTariffCode)
    {
        $ret = $this->repository->getRateInfo($businessCode, $mercTariffCode);
        if (!$ret) {
            Err("用户资费不存在!!:5040");
        }
        log::info("用户资费标准>>>>");
        log::info("businessCode:|".$businessCode);
        log::info("user_tariff_code:|".$mercTariffCode);
        log::info("rate:|".$ret['rate']);
        log::info("max_rate:|".$ret['max_rate']);
        log::info("base_rate:|".$ret['base_rate']);
        log::info("base_max_rate:|".$ret['base_max_rate']);
        return $ret;
    }

    /**
     * 根据业务类型，资费编码和交易金额计算到账金额
     */
    public function getReceiveAmt($tariffRate, $transAmount)
    {
        $ret = Money()->getReceiveAmt($tariffRate,$transAmount);
        $receiveAmt = $ret['receiveAmt'];
        $fee = $ret['fee'];
        $request['fee']=$fee;
        $request['receiveAmt'] = $receiveAmt;
        return $request;
    }
}
