<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:13
 */

namespace App\Modules\Pms\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Repository\CashOrderRepository;
use App\Modules\Transaction\Repository\TranTransOrderRepo;
use App\Modules\Transaction\Trans;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\DB;

class PmsUserUpgradeService extends Service
{
    public $summary;
    public $detail;
    public function __construct(CashOrderRepository $summary,TranTransOrderRepo $detail)
    {
        $this->summary = $summary;
        $this->detail = $detail;
    }

    public function getRules()
    {

    }

    public function pmsUserUpgrade(CommUserRepo $user,$request){
        $amount = config('const_user.'.$request['tariff_code'].'.amount');

        $business_code = config('interface.REQUEST_CODE.'.$request['tariff_code']);

        //获取用户信息
        $userInfo = $user->getUser($request['user_id']);

        if($amount == $request['amount']){
            $ret = Trans::service('ChannelTrans')
                ->with('business_code',$business_code)
                ->with('trans_amt',$amount)
                ->with('tariff_code',$userInfo['user_tariff_code'])
                ->with('user_id',$userInfo['user_id'])
                ->with('type','20')
                ->with('time',time())
                ->run('customChannel');
           
            $params = [
                'status' => '5',
                'update_time' => date('Y-m-d H:i:s'),
            ];

            if($ret['code'] == '0000'){
                return $this->updateOrder($ret,$params);
            }

        }else{
            Err('升级金额错误');
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