<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/5
 * Time: 19:23
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Access\Repository\TeamRelationRepo;
use Closure;
use Illuminate\Support\Facades\Log;

class CreateTeamMiddle extends Middleware
{
    public $repo;
    public $user;
    public function __construct(TeamRelationRepo $repo,CommUserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    public function handle($request, Closure $next)
    {
        Log::info('团推关系创建');

        $count = config('const_user.USER_MODEL_COUNT');

        $data = array(
            'id'               => ID(),
            'user_id'          => $request['user_id'],
            'user_name'        => $request['user_name'],
            'model_count'      => $count,
            'parent1'          => $request['recommendId'],
            'status'           => 1,
            'create_by'        => 'system',
            'create_time'      => date('Y-m-d H:i:s'),
            'update_by'        => 'system',
            'update_time'      => date('Y-m-d H:i:s'),
        );

        $teamInfo = $this->repo->getRelation($request['recommendId']);
        for($i = 1;$i < $count;$i++){
            $data['parent'.($i+1)] = $teamInfo['parent'.$i];
        }

        for($i = 5;$i <=10 ;$i++){
            $data['parent'.($i)] = $teamInfo['parent'.$i];
        }

        //保存数据库
        $this->repo->insert($data);
//        $this->repo->updateRecommendRela($request['id'],$request['recommendId']);
        return $next($request);
    }
}