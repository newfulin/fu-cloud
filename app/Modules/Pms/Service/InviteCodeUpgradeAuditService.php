<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:15
 */

namespace App\Modules\Pms\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\InviteCodeRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InviteCodeUpgradeAuditService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public  function InviteCodeUpgradeAudit(CommUserRepo $user, TranTransOrderRepo $order, InviteCodeRepo $code, $request)
    {

        //获取升级流水 汇总信息
        $DetailInfo = $order->getDetailOrderInfo($request['detail_id']);
        //检查流水状态
        if($DetailInfo['status'] == '2'){
            Err('已审批成功,不可重复审批');
        }

        //检查流水状态
        if($DetailInfo['status'] == '9'){
            Err('交易已关闭,不可审批');
        }

        DB::beginTransaction();
        try {
            //更新订单流水状态
            $order->updateDetailOrder($request['detail_id'],['status' => '2']);
            //更新用户等级
            $user->updateUser($request['user_id'],[
                'user_tariff_code' => $DetailInfo['level_name'],
                'level_name'       => $DetailInfo['level_name'],
            ]);
            //更新邀请码使用情况
            $params = array(
                'use_user_id' => $request['user_id'],
                'use_time' => date('Y-m-d H:i:s', time()),
                'state' => '20',
            );
            $code->updateState($DetailInfo['code'],$params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('流水修改失败', 9999);
        }
        return $request;

    }
    public function SendInvCode(CommUserRepo $user, TranTransOrderRepo $order, InviteCodeRepo $code)
    {
        Log::info('自动补发邀请码');


        return '关闭';

        // 单发 1157780932602869761

        $data = $this->createCode('1157780932602869761');
        $params = [];
        for ($i = 0; $i < 60;$i++){
            $data['id'] = ID();
            $data['code'] = strtoupper(MD5(ID()));
            $data['amount'] = 1000;
            $data['level_name'] = config('const_user.'.'VIP_USER'.'.code');
            $params[] = $data;
        }
        $ret = $code->insert($params);
        if($ret == false){
            Err('错误');
        }
        return count($params);







        $userArr = $order->getUserArr();
        $setNum = [
            'A1140' => 20,
            'A1233' => 5,
            'A2233' => 10,
        ];
        $params = [];
        $time = 0;
        foreach ($userArr as $k=>$v)
        {
            $codeNum = $code->getCodeNum($v['user_id']);
            $getNum = $setNum[$v['business_code']];

            if ($codeNum < $getNum) {
                $time = $time+1;
                $number = $getNum - $codeNum;
                Log::info($time.'||'.$getNum.'-'.$codeNum.'='.$number);
                $data = $this->createCode($v['user_id']);

                for ($i = 0; $i < $number;$i++){
                    $data['id'] = ID();
                    $data['code'] = strtoupper(MD5(ID()));
                    $data['amount'] = 1000;
                    $data['level_name'] = config('const_user.'.'VIP_USER'.'.code');
                    $params[] = $data;
                }
                Log::info('count='.count($params).'+'.$number);
            }
        }
        $ret = $code->insert($params);
        if($ret == false){
            Err('错误');
        }
        return count($params);
    }
    public function createCode($user_id){

        $data = [
            'user_id' => $user_id,
            'old_user_id' => $user_id,
            'state' => '10',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => $user_id,
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => $user_id
        ];
        return $data;
    }


}