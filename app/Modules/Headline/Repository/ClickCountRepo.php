<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 11:27
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\ClickCount;
use Illuminate\Database\Eloquent\Model;

class ClickCountRepo extends Repository
{
    public function __construct(ClickCount $model)
    {
        $this->model = $model;
    }

    //获取点赞数量
    public function getClickCount($request)
    {

      return  $this->model
            ->where([
                    'status' => '10',
                    'obj_id' => $request['obj_id']
                ])
            ->count();
    }

    //判断点赞状态
    public function judgeDataClick($request){
        return $this->model
            ->where([
                'obj_id' => $request['obj_id'],
                'user_id' => $request['user_id'],
                'status' => '10'
                ])
            ->first();
    }

    //取消点赞
    public function cancelDataClick($request)
    {
        return $this->model
            ->where([
                'obj_id' => $request['obj_id'],
                'user_id' => $request['user_id']
            ])
            ->update(
                ['status' => '20']
            );
    }

}