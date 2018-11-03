<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/24
 * Time: 15:48
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Repository\IndexImgRepo;
use App\Modules\Access\Repository\ShareControlRepository;
use App\Modules\Access\Repository\ShelfProductRepo;
use App\Modules\Access\Repository\TopLineRepo;

class TopService extends  Service{

    public function getRules()
    {
        return [];
    }
    protected $control;
    public function __construct(ShareControlRepository $control)
    {
        $this->control = $control;
    }

    public function getTopList($request)
    {
        if($request['status']){
            $ret = app()->make(TopLineRepo::class)->getRecoList($request);
            return $ret;
        }
        $ret =  app()->make(TopLineRepo::class)->getTopList($request);
        return $ret;
    }

    public function getTopInfo($request)
    {
        $ret =  app()->make(TopLineRepo::class)->getTopInfo($request);
        $count = $this->control->getCount($request['id']);
        $ret[]['count'] = $count;
        return $ret;
    }

    //获取首页头条
    public function getHomeTopList(){
        $ret = app()->make(TopLineRepo::class)->getHomeTopList();
        return array_chunk($ret,2);
    }

    public function getTopImg($request)
    {
        $ret = app()->make(IndexImgRepo::class)->getTopImg();
        return $ret;
    }
}