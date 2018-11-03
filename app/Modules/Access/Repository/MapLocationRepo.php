<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 9:35
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\MapLocation;

class MapLocationRepo extends Repository
{
    public function __construct(MapLocation $model)
    {
        $this->model = $model;
    }
    public function saveLocation($params)
    {
        $this->model->address  = $params['address'];
        $this->model->province = $params['province'];
        $this->model->city     = $params['city'];
        $this->model->district = $params['district'];
        $this->model->ip       = $params['ip'];
        $this->model->user_id  = $params['user_id'];
        $this->model->login    = $params['login'];
        return $this->model->save();
    }
    public function checkLocation($ip)
    {
        return optional($this->model->select('user_id','district','create_time')
            ->where('ip','127.0.0.1')
            ->orderBy('create_time','desc')
            ->first())
            ->toArray();
    }
}