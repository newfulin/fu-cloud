<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/21
 * Time: 15:41
 */

namespace Nxp\Team;

use Illuminate\Support\Facades\Log;
use Nxp\Team\Repository\NxpTeamRetionRepo;

Class Create{

    public $USER_MODEL_COUNT = 3;
    public $team;

    public function __construct(NxpTeamRetionRepo $team)
    {
        $this->team = $team;
    }

    /*
     * @desc 创建用户推荐关系
     * @param string user_id  用户ID
     * @param string user_name  用户名
     * @param string recommend_id  推荐用户
     */
    public function createTeamRelation($request){

        Log::info('团队关系创建');

        $teamInfo = $this->team->getRelation($request['recommend_id']);

        $count = $this->USER_MODEL_COUNT;

        $data = array(
            'id'               => ID(),
            'user_id'          => $request['user_id'],
            'user_name'        => $request['user_name'],
            'model_count'      => $count,
            'parent1'          => $request['recommend_id'],
            'status'           => 1,
            'create_by'        => 'system',
            'create_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
        );
        for($i = 1;$i < $count;$i++){
            $data['parent'.($i+1)] = $teamInfo['parent'.$i];
        }
        for($i = 5;$i <=10 ;$i++){
            $data['parent'.($i)] = $teamInfo['parent'.$i];
        }

        //保存数据库
        $this->team->insert($data);

        //更新团队关系
        $this->team->updateRecommendRela($request['user_id'],$request['recommend_id']);
    }
}