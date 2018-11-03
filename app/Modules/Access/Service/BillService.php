<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 15:48
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CollectCountRepo;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Finance;
use App\Modules\Meet\Repository\MeetingRecordRepo;
use App\Modules\Transaction\Trans;

class BillService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //查询用户资产    余额
    public function getBalance($request)
    {
        $ret =  Finance::service('BalanceInfoService')
            ->with('acct_id',1)
            ->with('user_id',$request['user_id'])
            ->run('getAllBalance');
        array_multisort(array_column($ret,'account_type'),SORT_ASC,$ret);
        $allBalance = 0.00;
        foreach($ret as $key => $val){
            $allBalance += $val['balance'];
            $ret[$key]['icon'] = config('common.assets_icon.'.$val['account_type'].'.icon');
        }
        $list['list'] = $ret;
        $list['allBalance'] = $ret[0]['balance'];
        return $list;
    }

    //提现页面数据接口
    public function getCash(CommUserRepo $user,$request)
    {
        //用户查余额
        $balance = Finance::service('BalanceInfoService')
            ->with('acct_code',$request['user_id'])
            ->with('acct_type',config('finance.ACCOUNT_TYPE_ASSET.code'))
            ->with('acct_obj',config('finance.ACCOUNT_OBJECT_USER.code'))
            ->with('acct_id',1)
            ->run('getBalance');

        $userInfo = Access::service('CommUserInfoService')
            ->with('user_id',$request['user_id'])
            ->run('getUserInfo');
        $ret['userInfo'] = $userInfo;
        $ret['balance']= $balance;
        $ret['cashrange']= config('const_user.GETCASHRANGE');
        return $ret;
    }

    //用户提现
    public function submitCashInfo(CommUserRepo $repo,$request)
    {
        $userInfo = $repo->getUser($request['user_id']);

//        if($userInfo['user_tariff_code'] == config('const_user.ORDINARY_USER.code')){
//            Err('请升级PLUS会员后进行提现');
//        }

        if(in_array($userInfo['user_tariff_code'],config('const_user.NOTGETCASH'))){
            Err('此等级暂不支持提现,由财务进行结算');
        }

        if($userInfo['cash_status'] == '02'){
            Err('暂时无法提现');
        }

        if($request['amount'] < config('const_user.GETCASHRANGE')){
            Err('最少提现金额为200元');
        }

        return Trans::service('ChannelTrans')
            ->with('business_code','A0700')
            ->with('trans_amt',$request['amount'])
            ->with('tariff_code',$userInfo['user_tariff_code'])
            ->with('user_id',$userInfo['user_id'])
            ->with('time',time())
            ->runTransaction('withdrawals');
    }

    //获取金豆数量
    public function getGoldBean($request){
        $bean = Finance::service('BalanceInfoService')
            ->with('acct_code',$request['user_id'])
            ->with('acct_type',config('finance.ACCOUNT_TYPE_LEND.code'))
            ->with('acct_obj',config('finance.ACCOUNT_OBJECT_USER.code'))
            ->with('acct_id',1)
            ->run('getBalance');
        return $bean;
    }

    //获取个人中心小部件
    public function getPersonalWidget(CollectCountRepo $collect,MeetingRecordRepo $meet,$request){

        $data['bean'] = Finance::service('BalanceInfoService')
            ->with('acct_code',$request['user_id'])
            ->with('acct_type',config('finance.ACCOUNT_TYPE_LEND.code'))
            ->with('acct_obj',config('finance.ACCOUNT_OBJECT_USER.code'))
            ->with('acct_id',1)
            ->run('getBalance');

        $data['collection'] = $collect->getMyCollectAllCount($request);

        $data['balance'] = Finance::service('BalanceInfoService')
            ->with('acct_code',$request['user_id'])
            ->with('acct_type',config('finance.ACCOUNT_TYPE_ASSET.code'))
            ->with('acct_obj',config('finance.ACCOUNT_OBJECT_USER.code'))
            ->with('acct_id',1)
            ->run('getBalance');

        $data['meet'] = $meet->getMyAttendMeetCount($request);
        return $data;
    }
}