<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 14:10
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommLevelRights;

class LevelRightsRepo extends Repository
{
    public function __construct(CommLevelRights $model)
    {
        $this->model = $model;
    }

    //vip 权益
    public function getLevelRightsList(){
        return $this->model
            ->select('id','level_name','rights_name','rights_desc','img_url')
            ->where([
                'level_name' => 'P1201',
                'status'     => '01'
            ])
            ->oRwhere('level_name','')
            ->get();
    }
}