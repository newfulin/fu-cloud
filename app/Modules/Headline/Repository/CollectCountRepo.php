<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 11:46
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CollectCount;
use Illuminate\Database\Eloquent\Model;

class CollectCountRepo extends Repository
{
    public function __construct(CollectCount $model)
    {
        $this->model = $model;
    }

    //获取收藏数量
    public function getCollectCount($request){
        return $this->model
            ->where([
                'obj_id' => $request['obj_id'],
                'status' => '10'
            ])
            ->count();
    }
}