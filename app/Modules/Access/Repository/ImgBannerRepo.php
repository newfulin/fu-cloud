<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/29
 * Time: 10:07
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\ImgBanner;
use Illuminate\Support\Facades\Cache;

class ImgBannerRepo extends Repository
{
    public function __construct(ImgBanner $model)
    {
        $this->model = $model;
    }
    // 获取展示图（首页）
    public function getBanner()
    {
        $ret = optional($this->model
            ->select('img_url')
            ->where('status','10')
            ->get())
            ->toArray();
        return $ret;
    }

    public function getImgBannerList()
    {
        $key = 'mall_home_banner';
        $minutes = 60;

//        return Cache::remember($key,$minutes,function() use ($key){
        $ret = optional($this->model
            ->select('id','title','desc','img_url','relation_type','relation_page')
            ->where('status','10')
            ->get())
            ->toArray();
        return $ret;
//        });
    }
}