<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/28
 * Time: 8:53
 */

namespace App\Modules\Headline\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\TopLine;

class TopLineRepo extends Repository
{
    public $model;
    public function __construct(TopLine $model)
    {
        $this->model = $model;
    }

    public function getTopList($request)
    {

        $ret =  optional($this->model
            ->select('id','title','author','show_type','attr1','attr2','attr3','browse_volume','top_type')
            ->where('top_type',$request['top_type'])
            ->where('top_status',1)
            ->orderBy('update_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        $ret = $ret['data'];
        return $ret;
    }

    public function getRecoList($request)
    {
        $ret =  optional($this->model
            ->select('id','title','author','top_type','show_type','attr1','attr2','attr3','browse_volume')
            ->where('top_status',1)
            ->orderBy('update_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        $ret = $ret['data'];

        return $ret;
    }

    //获取头条详情
    public function getTopInfo($request)
    {
        $ret = optional($this->model
            ->select('id','title','author','content','attr1','attr2','attr3','show_type','create_time','top_type','total_num','author_img','browse_volume')
            ->where('id',$request['id'])
//            ->where('top_status',1)
            ->first())
            ->toArray();

        //文章浏览量
        $this->setIncBrowse($request);

        return $ret;
    }

    //文章浏览量
    public function setIncBrowse($request){
        return $this->model
            ->where('id',$request['id'])
            ->increment('browse_volume');
    }

    //获取头条分享内容
    public function getShareInfo($request){
//        dd($request);
        return optional($this->model
            ->select('id','title','top_desc','attr1')
            ->where('id',$request['id'])
            ->first())
            ->toArray();
    }



}