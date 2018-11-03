<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 9:52
 */

namespace App\Modules\Pms\Service;


use App\Common\Contracts\Service;

use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Callback\Callback;
use App\Modules\Finance\Finance;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Pms\Events\InviteCodeAfterEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpgradeToExamineService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //修改 团队 店长
    public $afterEvent = [
//        ModifyTeamAfterEvent::class,
        InviteCodeAfterEvent::class
    ];

    public function handle(CashOrderRepository $order,CommUserRepo $user,$request){
        //获取升级流水 汇总信息
        $summaryInfo = $order->getSummaryOrder($request['detail_id']);

        //检查流水状态
        if($summaryInfo['status'] == '2'){
            Err('已审批成功,不可重复审批');
        }

        //检查流水状态
        if($summaryInfo['status'] == '9'){
            Err('交易已关闭,不可审批');
        }

        Log::info('---------请求财务 |'.$request['business_code'] . ' | ' . $request['user_id']);
        $code = Finance::service('CashierService')
            ->with('code',config('interface.FINANCE.'.$request['business_code']))
            ->with('orderId',$summaryInfo['id'])
            ->with('detailOrderId',$request['detail_id'])
            ->with('transAmount',$request['trans_amt'])
            ->run();

        Log::info('-------------------记账返回结果----------------'.$code);

        Log::info('------------------财务请求完成，修改流水、用户-------');
        $time = time();
        $chatParams = array(
            'status' => '2',
            'acct_res_code' => $code
        );
        $summaryParams = array(
            'update_time' => date("Y-m-d H:i:s"),
            'status' => '2',
            'acct_res_code' => $code
        );

        DB::beginTransaction();
        try {
            $func = $request['business_code'];
            Log::info('财务请求成功_请求码 |'.$func);
            Callback::service($func)
                ->with('detailId',$request['detail_id'])
                ->with('params',$chatParams)
                ->run('update');

            app()->make(CashOrderRepository::class)
                ->update($summaryInfo['id'], $summaryParams);

            //更新用户等级
            $level = config('interface.USER_CODE.'.$func);
            Log::info('财务请求成功_更新用户等级 |'.$level);
            $user->updateUser($request['user_id'],[
                'user_tariff_code' => $level,
                'level_name'       => $level
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('流水修改失败', 9999);
        }
        return $request;
    }
}