<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/10
 * Time: 8:56
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommPushTemplet;

class CommPushTempletRepo extends Repository
{
    public function __construct(CommPushTemplet $model)
    {
        $this->model = $model;
    }

    //查询短信模版
    public function getSmsTemplet($code)
    {
        return optional($this->model
                ->select('title','templet_id','content')
                ->where('business_code',$code)
                ->first())
                ->toArray();
    }
}