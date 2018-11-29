<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 8:51
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommCodeMasterRepo;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use App\Modules\Transaction\Trans;
use Illuminate\Support\Facades\DB;

class AgentUpgradeService extends Service
{
    public function __construct(CashOrderRepository $summary,TranTransOrderRepo $detail)
    {
        $this->summary = $summary;
        $this->detail = $detail;
    }


    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //代理商升级
    public function AgentUpgrade(CommUserRepo $user,CommCodeMasterRepo $master,$request){
        //判断邀请码 是否有效

        $userInfo = $user->getUser($request['user_id']);

        $business_code = config('interface.REQUEST_CODE.'.$request['tariff_code']);

        $ret = Trans::service('ChannelTrans')
            ->with('business_code',$business_code)
            ->with('trans_amt'    , 0)
            ->with('tariff_code'  , $userInfo['user_tariff_code'])
            ->with('user_id'      , $userInfo['user_id'])
            ->with('invite_code'  , $request['invite_code'])
            ->with('type'         , '10') //10邀请码升级  20缴费升级
            ->with('time'         , time())
            ->run('customChannel');

        $params = [
            'status' => '5',
            'update_time' => date('Y-m-d H:i:s'),
        ];

        if($ret['code'] == '0000'){
            return $this->updateOrder($ret,$params);
        }
    }

    public function updateOrder($ret,$params){
        DB::beginTransaction();
        try {
            $this->detail->update($ret['detailId'],$params);
            $this->summary->update($ret['summaryId'],$params);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Err('数据修改失败', 9999);
        }
        return '0000';
    }
}