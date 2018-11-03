<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:10
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\LevelRightsRepo;
use App\Modules\Misc\Repository\CommCodeMasterRepo;

class LevelRightsService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    //获取等级权益
    public function getLevelRightsList(LevelRightsRepo $repo,CommCodeMasterRepo $master){
        $ret = $repo->getLevelRightsList();

        foreach($ret as $key => $val){
            $ret[$key]->level_img = config('const_user.'.$val->level_name.'.code');
            $ret[$key]->level_name = config('const_user.'.$val->level_name.'.msg');
        }
        $arr['level'] = $ret;
        $masterCon = $master->getConfigureByKey('P1201');
        $arr['brief'] = $masterCon['property3'];
        return $arr;
    }
}