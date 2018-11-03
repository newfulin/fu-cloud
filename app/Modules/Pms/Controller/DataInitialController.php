<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 9:58
 * @desc 数据初始
 */

namespace App\Modules\Pms\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Transaction\Trans;

class DataInitialController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc RD贝系统账户充值 A0810
     */
    public function RDSystemRecharge(){
        return Trans::service('ChannelTrans')
            ->with('business_code','A0810')
            ->with('trans_amt',0)
            ->with('tariff_code','P1101')
            ->with('user_id','1131592936878881792')
            ->with('time',time())
            ->runTransaction('RDSystemRecharge');
    }

    /**
     * @desc 活动总负债转账1000w A0820 Transfer Accounts
     */
    public function ActivityLiabilitiesTA(){
        return Trans::service('ChannelTrans')
            ->with('business_code','A0820')
            ->with('trans_amt',0)
            ->with('tariff_code','P1101')
            ->with('user_id','1131592936878881792')
            ->with('time',time())
            ->runTransaction('ActivityLiabilitiesTA');
    }

    /**
     * @desc 企业预留负债转账800w A0830 Transfer Accounts
     */
    public function EnterpriseLiabilitiesTA(){
        return Trans::service('ChannelTrans')
            ->with('business_code','A0830')
            ->with('trans_amt',0)
            ->with('tariff_code','P1101')
            ->with('user_id','1131592936878881792')
            ->with('time',time())
            ->runTransaction('EnterpriseLiabilitiesTA');
    }

    /**
     * @desc 员工预留负债转账200w A0840 Transfer Accounts
     */
    public function StaffLiabilitiesTA(){
        return Trans::service('ChannelTrans')
            ->with('business_code','A0840')
            ->with('trans_amt',0)
            ->with('tariff_code','P1101')
            ->with('user_id','1131592936878881792')
            ->with('time',time())
            ->runTransaction('StaffLiabilitiesTA');
    }
}