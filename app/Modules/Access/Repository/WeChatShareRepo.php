<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 18:11
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\WeChatShare;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WeChatShareRepo extends Repository
{
    public function __construct(WeChatShare $model)
    {
        $this->model = $model;
    }

    public function getManageShare($store_id){
        return optional($this->model
            ->select('title', 'content')
            ->where('store_id', $store_id)
            ->first())
            ->toArray();
    }

    public function insertStoreShare($shareParams){
        return DB::table('wechat_share')->insertGetId($shareParams);
    }

    public function updateStoreShare($id, $params){
        return $this->model->where('id', $id)->update($params);
    }

    public function getShopShareInfo($id){
        return optional($this->model
            ->select('id', 'title', 'content', 'img_url', 'url', 'create_time')
            ->where('id', $id)
            ->first())
            ->toArray();
    }

    public function getShareInfo($request)
    {
        $ret = optional($this->model
            ->select('id','title', 'content', 'img_url', 'share_little_url', 'share_large_url', 'url','right_edge','bottom_edge')
            ->where([
                'status' => '10',
                'id' => $request['id']
            ]))->first();
        if (!$ret) {
            Err('分享链接不存在','7777');
        }
        return $ret;
    }

    public function getAppShare($id, $kind_of){
        $ret = optional($this->model
            ->select('id', 'title', 'content', 'img_url', 'url')
            ->where('id', $id)
            ->where('kind_of', $kind_of)
            ->first())
            ->toArray();
        if (!$ret) {
            Err('分享链接不存在','7777');
        }
        return $ret;
    }

    //微信分享
    public function getWechatShareInfo($request)
    {
        $ret = optional($this->model
            ->select('id', 'share_little_url', 'url')
            ->where('kind_of', '10')
            ->where('status','10')
            ->orderBy('sort', 'desc')
            ->orderBy('create_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        return $ret['data'];
    }

}