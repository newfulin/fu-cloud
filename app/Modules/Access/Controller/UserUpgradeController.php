<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 9:52
 * 用户升级Controller
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserUpgradeController extends Controller
{
    public function getRules()
    {
        return [
            'plusUpgrade' => [
                'recommend_code' => 'desc:推荐码',
                'upgrade_level' => 'required|desc:升级等级'
            ]
        ];
    }

    /**
     * @desc 用户升级
     */
    public function plusUpgrade(Request $request){
        $user_id = $request->user()->claims->getId();

        Log::info('用户升级| '.$user_id . ' 推荐码| '. $request->input('recommend_code') .' 升级等级| '.$request->input('upgrade_level'));
        return Access::service('UserUpgradeService')
            ->with('user_id',$user_id)
            ->with('recommend_code',$request->input('recommend_code'))
            ->with('upgrade_level',$request->input('upgrade_level'))
            ->run();
    }
}