<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/30
 * Time: 11:18
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\TopLine;
use App\Common\Models\WeChatShare;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WeChatShareRepository extends Repository{
    public $model;
    public function __construct(TopLine $model)
    {
        $this->model =$model;
    }

    public function getTopShare($id)
    {
        $ret = optional(DB::table('erp_top_line')
            ->select('id','title','con_id','author','top_desc','share_pic','top_type','top_desc')
            ->where('id',$id)
            ->get())
            ->toArray();

        if(!$ret) Err('没有此头条信息');

        $ret[0]->share_pic = $ret[0]->share_pic ? R($ret[0]->share_pic,false):config('const_share.APP.top_pic');
        $ret[0]->url = config('const_share.URL.top');
        switch($ret['0']->top_type){
            case '10':
                $ret['0']->top_type = '日常';
                break;
            case '20':
                $ret['0']->top_type = '心情';
                break;
            case '30':
                $ret['0']->top_type = '吐槽';
                break;
            case '40':
                $ret['0']->top_type = '关注';
                break;

        }

        return $ret;
    }
    
}
