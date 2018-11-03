<?php
/**
 * 红包使用 获取用户编号 同时作为是否发放红包用的.
 */
namespace App\Modules\Finance\Middleware\Process ;

use Illuminate\Support\Facades\Log;
use App\Modules\Finance\Repository\RedPacketRepository;
use App\Modules\Finance\Repository\InsuranceOrderRepository;

class UserNumberHb extends Process {

    /**
     * 比如红包进行特殊处理
     */
    public function getBookingOrder($request)
    {
        //Log::info("比如红包进行特殊处理");
        $policy = $request['policy'];
        $parBookingOrder = parent::getBookingOrder($request);
        if($policy=='K0300'){
            $flag = $this->checkHongbao($request);
            if(!$flag){
                return '0';
            }
        }
        return $parBookingOrder;
    }

    /**
     * 检查是否发放红包
     */
    protected function checkHongbao($request){
        //1,检查有没有可用红包
        $where=[];
        $userId = $request['userinfo']['user_id'];
        $where['granting_object'] = $userId;
        $where['status'] = '01';
        $where['packet_amount'] = '50.00';
        $retHb = app()->make(RedPacketRepository::class)->getMyPacket($where);
        $hongbaoList = [];
        $hongbao = "";//抓取一个红包
        foreach($retHb as $key => $value){
            $hongbaoList[$key] = $value;
            $hongbao = $value;
        }
        //没有可用的红包不再发放
        if(count($hongbaoList)==0){
             return false;
        }
        //2,是否交商共保 判断商业险是否购买
        $detailOrder = $request['detailOrder'];
        $outer_order_id = $detailOrder['outer_order_id'];
        if($outer_order_id!=null){
            $retInsurance = app()->make(InsuranceOrderRepository::class)->getEntityByOuterOrderId($outer_order_id);
            //biz_total_premium 商业险
            $biz_total_premium = $retInsurance['biz_total_premium'];
            if($biz_total_premium==0||$biz_total_premium==''){
                return false;
            }
        }else{
            return false;
        }
        return true;    
    }
}