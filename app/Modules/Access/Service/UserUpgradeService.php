<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 10:09
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Events\SwitchTeamRelationsAfterEvent;
use App\Modules\Access\Middleware\JudgeUserLevelMiddle;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Transaction\Trans;
use Illuminate\Support\Facades\Log;

class UserUpgradeService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        JudgeUserLevelMiddle::class
    ];

    public $afterEvent = [
        SwitchTeamRelationsAfterEvent::class
    ];

    public function handle(CommUserRepo $user,$request){

        $userInfo = $user->getUser($request['user_id']);
        if(!$userInfo){
            Err('用户信息获取失败');
        }

        if($userInfo['user_tariff_code'] == 'P1201'){
            Err('您已是VIP用户!');
        }

        $business_code = $this->judgeTeamRelation($request);

        $master = getConfigure($business_code,$request['upgrade_level']);

        $recommend = app()->make(CommUserRepo::class)->getReferralCode($request);

        $ret = Trans::service('ChannelTrans')
            ->with('business_code',$business_code)
            ->with('trans_amt',$master['property2'])
            ->with('tariff_code',$userInfo['user_tariff_code'])
            ->with('user_id',$request['user_id'])
            ->with('time',time())
            ->run('userUpGrade');
        if($recommend){
            $ret['recommend_id'] = $recommend['id'];
        }
        $ret['user_id'] = $request['user_id'];
        return $ret;
    }

    //判断有无推荐关系
    public function judgeTeamRelation($request){

        $param = [
            'user_id' => $request['user_id']
        ];
        if($request['recommend_code']){
            $userInfo = app()->make(CommUserRepo::class)->getReferralCode($request);

            if($userInfo['user_tariff_code'] == config('const_user.NEST_USER.code')){
                return 'A0110';
            }
            if(!$userInfo) Err('请输入正确的推荐码!');
            $param = [
                'user_id' => $userInfo['id']
            ];
        }

        $team = app('nxp-team')->query()
            ->getSuperiorRecommendAll($param);
        $business_code = '';
        foreach ($team as $key => $val){
            if($val['user_tariff_code'] == config('const_user.NEST_USER.code')){
                $business_code = ($val['user_id'] != '0') ? 'A0110' : 'A0120';
            }
        }

        Log::info('用户升级 .'.$business_code);
        return $business_code;
    }
}