<?php

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\ImgBannerRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoodsCon extends Controller
{
    public function getRules(){
        return [
            'getGoodsList' => [
                'page' => 'required',
                'pageSize' => 'required',
                'purpose' => 'desc:new-新品推荐,yesterday-昨日上新,today-今日上新,past-往日上新,sales-销量冠军',
                'goodsType' => 'desc:10-食品生鲜,20-服装配饰,30-文体保健,40-家居日化,50-母婴专区,60-特色自营',
                'highlight' => 'desc:10-推荐',
                'goodsClass' => 'desc:10普通商品20积分商品30团购商品40秒杀商品'
            ],
            'getLikeGoodsList' => [
                'page' => '',
                'pageSize' => '',
            ],
            'getGoodsInfo' => [
                'id' => 'required'
            ],
            'sortGoodsList' => [
                'page' => 'required',
                'pageSize' => 'required',
                'goods_type' => 'required|desc:10-食品生鲜,20-服装配饰,30-文体保健,40-家居日化,50-母婴专区,60-特色自营',
            ],
            'getRecommendGoods' => [
                'page' => 'required',
                'pageSize' => 'required',
            ],
            'getSearchList' => [
                'price' => 'desc:价格区间(数组)',
                'key_word' => 'desc:关键字',
                'sales' => 'desc:销量(desc)',
                'time' => 'desc:最新(desc)',
                'sort_price' => 'desc:价格排序,asc-升序,desc-降序',
                'page' => 'required',
                'pageSize' => 'required',
                'goodsClass' => 'desc:10普通商品20积分商品30团购商品40秒杀商品'
            ],
            'getSearchName'=> [
                'key_word' => 'required',
                'goodsClass' => 'desc:10普通商品20积分商品30团购商品40秒杀商品'
            ],
            'getCode' => [
                'product_id' => 'required|desc:产品id'
            ],
            'buyRecord' => [
                'id' => 'required|desc:商品id',
                'page' => 'required',
                'pageSize' => 'required'
            ],
        ];
    }
    /**
     * @desc 获取商品购买记录信息
     */
    public function buyRecord(Request $request){
        return Access::service('GoodsSer')
            ->with('id',$request->input('id'))
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('buyRecord');
    }
    /**
     * @desc 获取商品信息
     */
    public function getGoodsInfo(Request $request){
        Log::info('-----------------------'.json_encode($request->input()));

        return Access::service('GoodsSer')
            ->with('id',$request->input('id'))
            ->run('getGoodsInfo');
    }
    /**
     * @desc 获取喜欢商品列表
     */
    public function getLikeGoodsList(Request $request){
        return Access::service('GoodsSer')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getLikeGoodsList');
    }
    /**
     * @desc 获取商品分类
     */
    public function getClassify(Request $request){
        $goodsClassID = $request->input("pid");
        if (!$goodsClassID){
            $goodsClassID = 0;
        }
        return Access::service("GoodsSer")
            ->with("pid",$goodsClassID)
            ->run('getClassify');
    }

    public function getHomeClassify(Request $request){
        $num = $request->input("num");
        if (!$num){
            $num = 10;
        }
        return Access::service("GoodsSer")
            ->with("num",$num)
            ->run('getHomeClassify');
    }
    /**
     * @desc 获取商品列表
     */
    public function getGoodsList(Request $request){
        $goodsClass = $request->input('goodsClass');
        if (!$goodsClass) {
            $goodsClass = '10';
        }
        return Access::service('GoodsSer')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('purpose',$request->input('purpose'))
            ->with('goodsType',$request->input('goodsType'))
            ->with('highlight',$request->input('highlight'))
            ->with('goodsClass',$goodsClass)
            ->with('time',time())
            ->run('getGoodsList');
    }

    /**
     * @desc获取热品推荐
     * @param Request $request
     * @return mixed
     */
    public function getRecommendGoods(Request $request)
    {
        return Access::service('GoodsSer')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('getRecommendGoods');
    }

    /**
     * @desc获取二维码
     * @param Request $request
     * @return mixed
     */
    public function getCode(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        return Access::service('GoodsSer')
            ->with('user_id',$user_id)
            ->with('product_id',$request->input('product_id'))
            ->run('getCode');
    }
    /**
     * 分类查询
     * @param Request $request
     * @return mixed
     */
    public function sortGoodsList(Request $request)
    {
        return Access::service('GoodsSer')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('goods_type',$request->input('goods_type'))
            ->run('sortGoodsList');
    }

    /**
     * @desc 查询
     * @param Request $request
     * @return mixed
     */
    public function getSearchList(Request $request)
    {
        $goodsClass = $request->input('goodsClass');
        if (!$goodsClass) {
            $goodsClass = '10';
        }
        return Access::service('GoodsSer')
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->with('price',$request->input('price'))
            ->with('key_word',$request->input('key_word'))
            ->with('sales',$request->input('sales'))
            ->with('time',$request->input('time'))
            ->with('sort_price',$request->input('sort_price'))
            ->with('goodsClass',$goodsClass)
            ->run('getSearchList');
    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 查询商品
     */
    public function getSearchName(Request $request)
    {
        $goodsClass = $request->input('goodsClass');
        if (!$goodsClass) {
            $goodsClass = '10';
        }
        return Access::service('GoodsSer')
            ->with('key_word',$request->input('key_word'))
            ->with('goodsClass',$goodsClass)
            ->run('getSearchName');
    }
}