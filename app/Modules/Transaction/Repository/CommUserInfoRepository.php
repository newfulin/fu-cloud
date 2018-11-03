<?php

namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\CommUserInfo;


class CommUserInfoRepository extends Repository
{
    public function __construct(CommUserInfo $model)
    {
        $this->model = $model;
    }

    public function memCentre($userId)
    {
        $re = optional($this->model
            ->select('id','login_name','user_tariff_code','user_name')
            ->where('user_id',$userId)
            ->first())
            ->toArray();
        return $re;
    }
    public function getUserName($userId)
    {
        $re = optional($this->model
            ->select('user_name','headimgurl')
            ->where('user_id',$userId)
            ->first())
            ->toArray();
        return $re;
    }


    public function getCafeId($loginName)
    {
        $re = optional($this->model
            ->select('user_id')
            ->where('login_name',$loginName)
            ->first())
            ->toArray();
        return $re['user_id'];
    }


    public function changeLevel($level)
    {
        switch($level){
            case 'P1201':
            $ret = $this->model->where('level_name',$level)
                    ->update([
                    'user_tariff_code' => 'P1101',
                    'level_name' => 'P1101',
                 ]);
                break;
            case 'P1221':
                $ret = $this->model->where('level_name',$level)
                    ->update([
                        'user_tariff_code' => 'P1301',
                        'level_name' => 'P1301',
                    ]);
                break;
            case 'P1401':
                $ret = $this->model->where('level_name',$level)
                    ->update([
                        'user_tariff_code' => 'P1311',
                        'level_name' => 'P1311',
                    ]);
                break;
            case 'P1501':
                $ret = $this->model->where('user_level',$level)
                    ->update([
                        'user_tariff_code' => 'P1401',
                        'level_name' => 'P1401',
                    ]);
                break;
        }
        return $ret;
    }
}
