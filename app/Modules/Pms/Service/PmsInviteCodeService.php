<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 11:04
 */

namespace App\Modules\Pms\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\InviteCodeRepo;

class PmsInviteCodeService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //六个车合伙人升升级区代使用的邀请码
    public function pmsPartnerInviteCode(InviteCodeRepo $code)
    {
        $level = 'P1401';
        $number = '70';
        $partner = $this->createCode($level, $number);
        return $code->insert($partner);
    }

    //六个车合作商，车巢升级总代理PMS生成外部的邀请码
    public function pmsOperatorInviteCode(InviteCodeRepo $code)
    {
        $level = 'P1301';
        $number = '1100';
        $operator = $this->createCode($level ,$number);
        return $code->insert($operator);
    }

    //六个车合作商，车巢升级总代理PMS生成外部的邀请码
    public function pmsCarNestInviteCode(InviteCodeRepo $code)
    {
        $level = 'P1311';
        $number = '50';
        $operator = $this->createCode($level ,$number);
        return $code->insert($operator);
    }

    public function createCode($level,$number){
        $data = [
            'type' => '20',
            'level_name'=> $level,
            'amount' => '0',
            'state' => '10',
            'create_time' => date('Y-m-d H:i:s'),
            'create_by' => 'system',
            'update_time' => date('Y-m-d H:i:s'),
            'update_by' => 'system',
        ];

        for ($i = 0; $i < $number;$i++){
            $data['id'] = ID();
            $data['code'] = strtoupper(MD5(ID()));
//            $data['amount'] = $amount;
//            $data['level_name'] = config('const_user.'.$config.'.code');
            $arr[] = $data;
        }
        return $arr;
    }
}