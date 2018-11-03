<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 18:03
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\ClickCountRepo;
use App\Modules\Access\Repository\CollectCountRepo;
use App\Modules\Access\Repository\TopLineRepo;
use App\Modules\Meet\Repository\ShareControlRepo;

class HeadLineService extends Service
{
    public $click;
    public $collect;
    public $share;
    public function __construct(ClickCountRepo $click,CollectCountRepo $collect,ShareControlRepo $share)
    {
        $this->click = $click;
        $this->collect = $collect;
        $this->share = $share;
    }

    public function getRules(){

    }

    //头条列表
    public function getHeadLineList(TopLineRepo $repo,$request){
        if($request['type']){
            $ret =  $repo->getHeadLineListByType($request);
        }else{
            $ret =  $repo->getHeadLineNewest($request);
        }

        return $this->HandleData($ret);
    }

    //获取头条详情
    public function getHeadLineInfo(TopLineRepo $repo,$request){
        $ret = $repo->getHeadLineInfo($request);
        $ret['clike_count'] = $this->click->getClickCount(['obj_id' => $ret['id']]);
        $ret['forward_count'] = $this->share->getShareCount(['share_id' => $ret['id']]);
        $ret['content'] = makeJsContent($ret['content']);
        return $ret;
    }

    //文章点赞
    public function setIncLike(TopLineRepo $repo,$request){
        return $repo->setIncLike($request);
    }

    //数据处理
    public function HandleData($ret){

        foreach ($ret as $key => $val){
            $ret[$key]['top_type_msg'] = config('const_headline.type.'.$val['top_type'].'.msg');
            $ret[$key]['top_type_color'] = config('const_headline.type.'.$val['top_type'].'.color');

            $ret[$key]['clike_count'] = $this->click->getClickCount(['obj_id' => $val['id']]);

            $ret[$key]['collection_count'] = $this->collect->getCollectCount(['obj_id' => $val['id']]);
        }
        return $ret;
    }
}