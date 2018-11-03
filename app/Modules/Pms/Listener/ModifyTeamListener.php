<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 14:59
 */

namespace App\Modules\Pms\Listener;


use App\Modules\Access\Repository\TeamRelationRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;


class ModifyTeamListener implements ShouldQueue
{
    public function __construct(TeamRelationRepo $team)
    {
        $this->team = $team;
    }

    public function handle($event){
        $request = $event->request;
        $this->modifyTeam($request['user_id'],$request['user_id']);
    }

    public function modifyTeam($user_id,$superior_id){
        Log::info('修改 团队 店长 |: '.$user_id . '上级ID |' .$superior_id);
        $param = [
            'parent6' => $superior_id
        ];
        app('nxp-team')->query()
            ->updateSelfRelation($user_id,$param);

        $team = $this->team->getParent1Info($user_id);
        foreach ($team as $key => $val){
            //更新非店长用户 团队关系
            if($val->user_tariff_code != config('const_user.NEST_USER.code')){
                $this->modifyTeam($val->user_id,$superior_id);
            }
        }
    }
}