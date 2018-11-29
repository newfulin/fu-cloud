<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 9:40
 * pms 升级审核
 */

namespace App\Modules\Pms\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Pms\Pms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpgradeToExamineController extends Controller
{
    public function getRules()
    {
        return [
            'upgradeAudit' => [
                'detail_id'     => 'required|desc:流水ID',
                'business_code' => 'required|desc:请求码',
                'trans_amt'     => 'required|desc:交易金额',
                'user_id'       => 'required|desc:升级用户ID'
            ],
            'pmsAreaUserUpgrade' => [
                'user_id' => 'required|desc:用户ID',
                'tariff_code' => 'required|desc:升级等级',
                'amount' => 'required|desc:金额'
            ],
            'InviteCodeUpgradeAudit' => [
                'detail_id'     => 'required|desc:流水ID',
                'business_code' => 'desc:请求码',
                'trans_amt'     => 'desc:交易金额',
                'user_id'       => 'required|desc:升级用户ID'
            ],
            'SendInvCode' => [
                'lock' => 'required|desc:验证'
            ]
        ];
    }
    /**
     * @desc 用户自动补发邀请码
     */
    public function SendInvCode(Request $request){
        $lock = $request->input('lock');
        if ($lock != 'Miller') {
            Err('验证失败');
        }
        return Pms::service('InviteCodeUpgradeAuditService')
            ->run('SendInvCode');
    }
    /**
     * @desc PMS 用户升级审核
     */
    public function upgradeAudit(Request $request){
        Log::info('pms 用户升级审核');
        return Pms::service('UpgradeToExamineService')
            ->with('detail_id'    , $request->input('detail_id'     ))
            ->with('business_code', $request->input('business_code' ))
            ->with('trans_amt'    , $request->input('trans_amt'     ))
            ->with('user_id'      , $request->input('user_id'       ))
            ->run();
    }

    /**
     * @desc PMS邀请码升级审核
     */
    public function InviteCodeUpgradeAudit(Request $request)
    {
        Log::info('pms 邀请码给用户升级审核');
        return Pms::service('InviteCodeUpgradeAuditService')
            ->with('detail_id'    , $request->input('detail_id'     ))
            ->with('business_code', $request->input('business_code' ))
            ->with('trans_amt'    , $request->input('trans_amt'     ))
            ->with('user_id'      , $request->input('user_id'       ))
            ->run('InviteCodeUpgradeAudit');
    }
}