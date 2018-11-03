<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/6/9
 * Time: 13:55
 */

namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\ImgHome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImgHomeRepo extends Repository
{
    public function __construct(ImgHome $model)
    {
        $this->model = $model;
    }

    //广告模块
    public function getAdModularHomeList($request)
    {
        return optional($this->model
            ->select('id', 'banner', 'sort', 'title', 'desc', 'jump_url', 'jump_id')
            ->where([
                'type' => $request['type'],
                'on_status' => '10'
            ])
            ->orderBy('update_time', 'desc')
            ->first()
        )->toArray();
    }
}