<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/8
 * Time: 14:34
 */
namespace App\Modules\Access\Listener ;

use App\Modules\Access\Repository\TeamRelationRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

//
class TeamReleationListener implements ShouldQueue{

    public $repo;

    public function __construct(TeamRelationRepo $repo)
    {
        $this->repo= $repo;
    }


    public function handle($event)
    {
        $request = $event->request;
        Log::info('团队 team_level 更新 |'.$request['user_id'] . '推荐ID' . $request['recommendId']);
        return $this->repo->updateRecommendRela($request['user_id'],$request['recommendId']);

    }

}