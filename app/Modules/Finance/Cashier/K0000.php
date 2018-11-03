<?php
/**
 * Created by PhpStorm.
 * User: mac_mini
 * Date: 2018/9/13
 * Time: 下午2:04
 */

namespace App\Modules\Finance\Cashier;

use Illuminate\Support\Facades\Log;

class K0000
{

    /**
     * 处理code,转换为数字,便于等级大小比较
     * @param $code
     * @return bool|string
     */
    public function getCodeLevel($code){
         return substr($code,1);
    }

    /**
     * 用户等级(数字)比较
     * @param $codeLevelA
     * @param $codeLevelB
     * @return bool
     */
    public function compareLevel($codeLevelA,$codeLevelB){
         //Log::info($codeLevelA."?".$codeLevelB);
         if($codeLevelA>=$codeLevelB){
             //Log::info("true");
             return true;
         }else{
             //Log::info("false");
             return false;
         }
    }

    /**
     * 用户等级(Code码)比较
     * @param $codeA
     * @param $codeB
     * @return bool
     */
    public function compareCode($codeA,$codeB){
        $codeLevelA = $this->getCodeLevel($codeA);
        $codeLevelB = $this->getCodeLevel($codeB);
        return $this->compareLevel($codeLevelA,$codeLevelB);
    }

    /**
     * 获取最近的高级别用户信息
     */

    public function getNextLevelUserInfo($retUserInfo,$level,$depth)
    {
        $i = 1;
        foreach ($retUserInfo as $key => $userInfo ){
            $user_tariff_code = $userInfo['user_tariff_code'];
            if($i>$depth){
                if($this->compareCode($user_tariff_code,$level)){
                    $depthInfo = $i;
                    $userInfo['depth'] = $depthInfo ;
                    return $userInfo;
                }
            }
            $i++;
        }
        return null;
    }

    /**
     * 获取分润分支记账流水
     */

    public function getBranchBookingOrder($markBookingOrder,$param){
        $markBookingOrder['id'] = ID();
        $markBookingOrder['batch_detail_id'] = $param['batch_detail_id'];
        $markBookingOrder['process_id'] = $param['process_id'];
        $markBookingOrder['credit_amount'] = $param['credit_amount'];
        $markBookingOrder['remark'] = $param['remark'];
        return $markBookingOrder;
    }

}