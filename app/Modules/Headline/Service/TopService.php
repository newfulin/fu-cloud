<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 17:26
 */

namespace App\Modules\Headline\Service;


use App\Common\Contracts\Service;
use App\Modules\Headline\Repository\ClickCountRepo;
use App\Modules\Headline\Repository\CollectCountRepo;
use App\Modules\Headline\Repository\ShareControlRepository;
use App\Modules\Headline\Repository\ShelfProductRepo;
use App\Modules\Headline\Repository\TopLineRepo;

class TopService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $click;
    public $collect;
    public $share;
    public function __construct( ShareControlRepository $share,ClickCountRepo $click, CollectCountRepo $collect)
    {
        $this->click = $click;
        $this->collect = $collect;
        $this->share = $share;
    }

    /**
     * @desc 获取头条列表
     */
    public function getTopList($request)
    {
        if($request['top_type']){
            $ret = app()->make(TopLineRepo::class)->getTopList($request);
        }else{
            $ret = app()->make(TopLineRepo::class)->getRecoList($request);
        }
        return $this->HandleData($ret);


    }

    /**
     * @desc 获取头条详情
     */
    public function getTopInfo($request)
    {
        $ret = app()->make(TopLineRepo::class)->getTopInfo($request);
//        dd($ret);
        $ret['click_count'] = $this->click->getClickCount(['obj_id' => $ret['id']]);
        $ret['forward_count'] = $this->share->getShareCount(['share_id' => $ret['id']]);
        $ret['content'] = makeJsContent($ret['content']);
        return $ret;

    }

    //头条列表数据处理
    public function HandleData($ret)
    {
        foreach ($ret as $key => $val){
            $ret[$key]['top_type_msg'] = config('const_headline.type.'.$val['top_type'].'.msg');
            $ret[$key]['top_type_color'] = config('const_headline.type.'.$val['top_type'].'.color');

            $ret[$key]['clike_count'] = $this->click->getClickCount(['obj_id' => $val['id']]);

            $ret[$key]['collection_count'] = $this->collect->getCollectCount(['obj_id' => $val['id']]);
        }

        return $ret;
    }

}