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
use App\Common\Models\IndexImg;
use Illuminate\Support\Facades\Cache;

class IndexImgRepo extends Repository
{
    public function __construct(IndexImg $model)
    {
        $this->model = $model;
    }

    public function getTopImg()
    {
        $ret = optional($this->model
            ->select('img_url')
            ->inRandomOrder()
            ->limit(1)
        ->get())
            ->toArray();
        if($ret[0]){
            $ret[0]['img_url'] = R($ret[0]['img_url'],false);
        }
        return $ret[0]['img_url'];
    }
}