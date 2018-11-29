<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/28
 * Time: 14:45
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\ShareControl;
use Illuminate\Database\Eloquent\Model;

class ShareControlRepository extends Repository
{
    public function __construct(ShareControl $model)
    {
        $this->model = $model;
    }

    /**
     * @desc 获取统计量
     */
    public function getShareCount($request)
    {
        $re = $this->model
            ->where('share_id',$request['share_id'])
            ->count();
        return $re;

    }
}