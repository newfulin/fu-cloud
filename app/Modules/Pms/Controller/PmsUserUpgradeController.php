<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 9:12
 * desc PMS 用户升级
 */

namespace App\Modules\Pms\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Pms\Pms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PmsUserUpgradeController extends Controller
{
    public function getRules()
    {
        return [
            'pmsUserUpgrade' => [
                'user_id' => 'required|desc:用户ID',
                'tariff_code' => 'required|desc:升级等级',
                'amount' => 'required|desc:金额'
            ],
            'pmsAreaUserUpgrade' => [
                'user_id' => 'required|desc:用户ID',
                'tariff_code' => 'required|desc:升级等级',
                'amount' => 'required|desc:金额'
            ]
        ];
    }

    /**
     * @desc pms用户升级
     */
    public function pmsUserUpgrade(Request $request){
        Log::info('pms 用户升级 user_id| ' . $request->input('user_id').' tariff_code | '.$request['tariff_code']);
        Log::info('pms 用户升级 tariff_code| ' . $request['tariff_code']);
        Log::info('pms 用户升级 amount| ' . $request['amount']);

        return Pms::service('PmsUserUpgradeService')
            ->with('user_id',$request->input('user_id'))
            ->with('tariff_code',$request->input('tariff_code'))
            ->with('amount',$request->input('amount'))
            ->run('pmsUserUpgrade');
    }

    /**
     * @desc pms用户区代 升级
     */
    public function pmsAreaUserUpgrade(Request $request){
        Log::info('pms 用户升级 user_id| ' . $request->input('user_id').' tariff_code | '.$request['tariff_code']);
        Log::info('pms 用户升级 tariff_code| ' . $request['tariff_code']);
        Log::info('pms 用户升级 amount| ' . $request['amount']);

        return Pms::service('PmsUserUpgradeService')
            ->with('user_id',$request->input('user_id'))
            ->with('tariff_code',$request->input('tariff_code'))
            ->with('amount',$request->input('amount'))
            ->run('pmsAreaUserUpgrade');
    }




}