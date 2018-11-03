<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/8
 * Time: 19:06
 */

namespace App\Modules\Access\Listener;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\TeamRelationRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
//
class SwitchTeamRelationsListener implements ShouldQueue
{
    public function __construct(TeamRelationRepo $team)
    {
        $this->team = $team;
    }

    public function handle($event)
    {
        $request = $event->request;

        if(isset($request['recommend_id']) && $request['recommend_id']){
            $this->switchTeamRelations($request);
//            return app('nxp-team')->query()
//                ->switchTeamRelations($request);
        }
    }

    public function switchTeamRelations($request){
        Log::info('团队用户切换 | 推荐用户: '.$request['recommend_id'] . ' | 切换用户 : '.$request['user_id']);
        app('nxp-team')->query()
            ->switchTeamRelations($request);
        $team = $this->team->getParent1Info($request['user_id']);
        foreach ($team as $key => $val){
            //更新非店长用户 团队关系
            if($val->user_tariff_code != config('const_user.NEST_USER.code')){
                $param = [
                    'recommend_id' => $request['user_id'],
                    'user_id' => $val->user_id,
                ];
                $this->switchTeamRelations($param);
            }
        }
    }
}