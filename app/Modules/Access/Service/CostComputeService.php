<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 8:43
 * 会议退款 费用计算
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Meet\Repository\MeetingInfoRepo;
use App\Modules\Transaction\Repository\MeetingOrderRepo;

class CostComputeService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function handle(MeetingInfoRepo $meet,MeetingOrderRepo $order,$request){
        $meetInfo = $meet->getCostDetails($request['meet_id']);
        $costDetails = json_decode($meetInfo['cost_details']);

        //获取会议推广流水  推广
        $extensionCount = $order->getOrderByCode($request,'A0350');
        //获取会议红包领取流水 会务
        $meetingCount = $order->getOrderByCode($request,'A0360');

        $data['extension_fee'] = Money()->calc($costDetails->extension->price , '*' , $extensionCount);
        $data['meetingn_fee'] = Money()->calc($costDetails->meeting->price , '*' , $meetingCount);

        //总费用
        $data['total_cost'] = Money()->add($data['extension_fee'],$data['meetingn_fee']);

        //获取会议 金额
        $meetInfo = $meet->getCostDetails($request['meet_id']);
        $data['refund_fee'] = Money()->sub($meetInfo['total_amount'],$data['total_cost']);

        return $data;
    }
}