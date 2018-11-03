<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 15:40
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Finance\Finance;
use App\Modules\Transaction\Trans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{
    public function getRules()
    {
        return [
            'getBillInfo' => [
                'direction' => 'required|in:0,1,2',
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'getListOrder' => [
                'page'      => 'required',
                'pageSize'  => 'required'
            ],
            'submitCashInfo' => [
                'amount' => 'required'
            ]
        ];
    }

    /**
     * @desc 用户账单查询接口  0 全部账单 1支出 2收入
     */
    public function getBillInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("用户账单查询:|" . $user_id);

        return Finance::service('BalanceInfoService')
            ->with('user_id',$user_id)
            ->with('direction',$request->input('direction'))
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getBookOrderList');
    }

    /**
     * @desc 查询资产
     */
    public function getBalance(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("用户资产查询:|" . $user_id);

        return Access::service('BillService')
            ->with('acct_id',1)
            ->with('user_id',$user_id)
            ->run('getBalance');
    }

    /**
     * @desc 提现页面数据
     */
    public function getCash(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("提现页面数据:|" . $user_id);
        return Access::service('BillService')
            ->with('user_id',$user_id)
            ->run('getCash');
    }

    /**
     * @desc 用户提现
     */
    public function submitCashInfo(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("用户提现:|" . $user_id);
        return Access::service('BillService')
            ->with('user_id',$user_id)
            ->with('amount',$request->input('amount'))
            ->run('submitCashInfo');
    }

    /**
     * @desc 查询流水  升级账单明细
     */
    public function getListOrder(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("查询用户流水:|" . $user_id);

        return Trans::service('TranTransOrderService')
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getListTransOrder');
    }

    /**
     * @desc 获取个人中心小部件
     * @return string bean 金豆数量
     * @return string collection 收藏数量
     * @return string balance 账户余额数量
     * @return string meet 会议数量
     */
    public function getPersonalWidget(Request $request){
        $user_id = $request->user()->claims->getId();
        Log::info("获取个人中心小部件:|" . $user_id);
        return Access::service('BillService')
            ->with('user_id',$user_id)
            ->run('getPersonalWidget');
    }
}