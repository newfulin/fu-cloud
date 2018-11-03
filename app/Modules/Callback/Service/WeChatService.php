<?php

namespace App\Modules\Callback\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\PurchaseOrderRepo;
use App\Modules\Access\Repository\WxPayOrderRepo;
use App\Modules\Access\Repository\WxPayOrderRepository as WeChatFlow;
use App\Modules\Callback\Callback;
use App\Modules\Finance\Finance;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Transaction\Repository\CardDetailRepo;
use App\Modules\Transaction\Repository\CoffeeConsumeOrderRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use App\Modules\Transaction\Repository\TranOrderRepo;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Unirest\Request;

class WeChatService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

//=============================================================================================
    public function Resale($request)
    {
        Log::info('-------转售订单回调结果处理---------' . json_encode($request));
        $data = $request['data'];
        // 财务记账 修改订单
        $re = $this->handleOrder($data);
        // 卡券转售
        $user_id = $re['user_id'];
        $cardId = $data['attach'];
        // 参数设置
        $params = $re;
        $params['resale'] = '01';
        Log::info('-------记账成功，参数设定---------$cardId='.$cardId.'--------$params'.json_encode($params));
        app()->make(CardDetailRepo::class)->updCardUser($cardId,$params);
        // 消息推送
        Access::service('JPushService')
            ->with('message_type', 'USER_CARD_SUC')
            ->with('number', '1')
            ->with('user_id', $user_id)
            ->with('target', $user_id)
            ->run('singlePush');

        return $request;
    }
//=============================================================================================

    //微信回调结果处理 (用户升级,咖啡豆充值)
    public function nativePay($request)
    {
        Log::info('-------会员升级回调结果处理---------' . json_encode($request));
        $data = $request['data'];

        $re = $this->handleOrder($data);
        $user_id = $re['user_id'];
        $business_code = $re['business_code'];

        $func = $re['business_code'];
        //根据business_code (A0110,A0120) 跳转对应操作 处理
        Callback::service($func)
            ->with('data',$re)
            ->run();

//        app()->make(CommUserInfoRepository::class)
//            ->updateUserLevel($user_id, config('interface.USER_CODE.'.$business_code));
        return;
    }

    //咖啡豆充值回调

    public function caseChannel($request)
    {
        Log::info('----------------------' . json_encode($request));
        return false;
    }

    public function handleOrder($data)
    {
        $detailId = $data['out_trade_no'];
        $result_code = $data['result_code'];
        $state = 2;
        if ($result_code != 'SUCCESS') {
            $state = 3;
        }
        $total_fee = Money()->getFen2Yuan($data['total_fee']);
        $time = $data['time'];

        // 根据请求码 查询对应微信明细流水
        $func = $data['attach'];
        $detailOrder = Callback::service($func)
            ->with('detailId',$detailId)
            ->run('getDetailOrder');

        // 查询汇总流水
        $summaryOrder = app()->make(CashOrderRepository::class)
            ->getSummaryOrder($detailId);

        $trans_amt = $detailOrder['trans_amt'];
        $user_id = $summaryOrder['user_id'];

        // 判断交易额
        Log::info('交易金额判断' . '|订单金额=' . $trans_amt . '|回调金额=' . $total_fee);
        if ($total_fee != $trans_amt) {
            Err('交易金额不一致', '9999');
        }

        // 请求财务记账，成功返回code'0000'，失败'false'
        Log::info('----------------请求财务记账-------------');
        Log::info('----参数设置-----|财务请求码=' . config('interface.FINANCE.' . $detailOrder['business_code']) . '---|汇总流水=' . $summaryOrder['id'] . '---|明细流水' . $detailId . '---|交易金额' . $trans_amt);

        $re = Finance::service('CashierService')
            ->with('code', config('interface.FINANCE.' . $detailOrder['business_code']))
            ->with('orderId', $summaryOrder['id'])
            ->with('detailOrderId', $detailId)
            ->with('transAmount', $trans_amt)
            ->run();
        Log::info('-------------------记账返回结果----------------' . $re);

        if ($re != '0000') {
            Err('财务记账请求失败', '9999');
        }

        // 修改微信流水、明细流水、汇总流水、会员等级
        // 参数设置
        Log::info('------------------财务请求完成，修改流水、用户-------');
        $chatParams = array(
            'time_expire' => $time,
            'state' => $state,
            'status' => $state,
            'acct_res_code' => $re
        );
        $summaryParams = array(
            'update_time' => $time,
            'status' => $state,
            'acct_res_code' => $re
        );

        DB::beginTransaction();
        try {

            Callback::service($func)
                ->with('detailId',$detailId)
                ->with('params',$chatParams)
                ->run('update');

            app()->make(CashOrderRepository::class)
                ->update($summaryOrder['id'], $summaryParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('订单修改失败', 9999);
        }
        $array = array(
            'user_id'       => $user_id,
            'business_code' => $detailOrder['business_code'],
            'order_id'      => $detailOrder['id']
        );
        return $array;
    }
}