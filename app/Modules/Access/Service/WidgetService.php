<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/28
 * Time: 18:39
 */
namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CommNoticeRepo;
use App\Modules\Access\Repository\ImgBannerRepo;
use App\Modules\Access\Repository\UpdateBriefRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use Illuminate\Support\Facades\Config;

class WidgetService extends Service{

    public $repo;
    public $img;
    public $user;
    public function __construct(CommNoticeRepo $repo,ImgBannerRepo $img,CommUserInfoRepository $user)
    {
        $this->repo = $repo;
        $this->img = $img;
        $this->user = $user;
    }
    public function getRules()
    {
        return [];
        // TODO: Implement getRules() method.
    }
    public function getHelpType($request)
    {
        $type = config('const_user.HELP_TYPE.code');
        $ret = $this->repo->getNoticeByType($type,$request['keyWord'],$request['pageSize']);
        return $ret['data'];
    }
    public function getHelpList()
    {
        $noticeType = config('const_user.HELP_TYPE.code');
        $type = ['01', '02', '03', '04','05','06','07','08'];
        $ret = [];
        foreach ($type as $key => $val) {
            $re = $this->repo->getHelpList($noticeType,$val);
            $res['list'] = $re;
            $res['open'] = false;
            $res['name'] = config('const_widget.HELP.type.'.$val);
            $ret[] = $res;

        }
        return $ret;
    }
    /**
     * 获取轮播图
     */
    public function getWidgetBanner()
    {
        return $this->img->getImgBannerList();
    }


    //获取公告
    public function getListWidget($type,$keyWord = '',$pageSize = 6)
    {
        $ret = $this->repo->getNoticeByType($type,$keyWord,$pageSize);
        return $ret['data'];
    }
    
    //获取车辆小部件
    public function getVehicleList()
    {
        //新車上架
        $ret['new_car'] = $this->getVehicleListByStatus(config('const_param.NEW_CAR.code'),8);
        //热销车辆
        $ret['selling_car'] = $this->getVehicleListByStatus(config('const_param.SELLING_CAR.code'),4);
        return $ret;
    }

    public function getVehicleListByStatus($status,$pageSize)
    {
        return Access::service('VehicleInfoService')
            ->with('status',$status)
            ->with('pageSize',$pageSize)
            ->run('getVehicleListByStatus');
    }

    //获取公告列表
    public function getNoticeList($request)
    {
        $type = $request['type'];
        $ret = $this->repo->getNoticeByType($type,$request['key_word'],$request['pageSize']);
        return $ret['data'];
    }

    //获取公告详情
    public function getNoticeInfo($request)
    {
        $ret = $this->repo->getNoticeInfoById($request['id']);
        $ret['notice_content'] = makeJsContent($ret['notice_content']);
        return $ret;
    }
}