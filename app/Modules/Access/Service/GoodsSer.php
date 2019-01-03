<?php
namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Middleware\CheckStoreStatusMiddle;
use App\Modules\Access\Repository\GoodsClassifyRepo;
use App\Modules\Access\Repository\GoodsInfoRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GoodsSer extends Service
{
    public function getRules()
    {

    }
    protected $user;
    protected $goods;
    protected $goods_classify;
    public function __construct(CommUserInfoRepository $user, GoodsInfoRepo $goods, GoodsClassifyRepo $goods_classify)
    {
        $this->user = $user;
        $this->goods = $goods;
        $this->goods_classify = $goods_classify;
    }
    public $middleware = [
        CheckStoreStatusMiddle::class => [
            'only' => 'getGoodsInfo'
        ]
    ];
    // 获取商品购买记录信息
    public function buyRecord($request)
    {
        $re = $this->goods->buyRecord($request);
        foreach ($re as $k=>$v) {
            $re[$k]['user_name'] = $this->userTextDecode($v['user_name']);
        }
        return $re;
    }
    //微信 特殊昵称处理 emoji 处理
    public function userTextDecode($str){
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }
    // 获取商品信息
    public function getGoodsInfo($request)
    {
        $id = $request['id'];
        $buyRecord =  $this->goods->getBuyRecord($id);
        $count = count($buyRecord);
        $headImg = [];
        if ($count<=10) {
            for ($i=0;$i<$count;$i++) {
                $headImg[] = $buyRecord[$i];
            }
        } else {
            for ($i=0;$i<10;$i++) {
                $headImg[] = $buyRecord[$i];
            }
            $headImg['9'] = 'http://mall.qcznkj.com/assets/morePurchaseRecord.png';
        }
        $ret =  $this->goods->getGoodsInfo($id);
        if ($ret){
            $data = array(
                'see' => $ret['see'] +1,
            );
            $this->goods->addCount($id,$data);

            $ret = $this->joinImg($ret,'info');
        }
        $ret['count'] = $count;
        $ret['headImg'] = $headImg;
        if($request['error']){
            $ret['error'] = $request['error'];
        }
        return $ret;
    }

    public function getCode($request)
    {
       
        $url = 'http://mall.melenet.com/?userId='.$request['user_id'].'&static/html/redirect.html?app3Redirect=#/pages/DetailsPage/index?id='.$request['product_id'];
        $dir = 'Data/upload/qrcode/'.'/qccode_'.rand(1000,9999).'.png';
        QrCode::format('png')->size('200')->margin(0)->generate($url,$dir); //保存路径);
        return  R(substr($dir,5));
    }
    // 获取喜欢商品列表
    public function getLikeGoodsList($request)
    {
        $arr =  $this->goods->getLikeGoodsList();
        $arr = $this->joinImg($arr,'list');
        $ret = $arr;

        return $ret;
    }
    // 获取商品列表
    public function getGoodsList($request)
    {
        $ret =  $this->goods->getGoodsList($request);
        if (!$ret) {
            return $ret;
        }
        $ret = $this->joinImg($ret,'list');
        if ($request['purpose'] == 'sales') {
            foreach ($ret as $k=>$v) {
                $ret[$k]['name'] = $this->cutContent($v['name'],7);
                $ret[$k]['introduce'] = $this->cutContent($v['introduce'],18);
            }
        }
        return $ret;
    }
    // 字段处理
    public function cutContent($content,$length)
    {
        if (strlen($content)>$length) $content=mb_substr($content, 0, $length, 'utf8').'...';
        return $content;
    }
    // 图片处理
    public function joinImg($ret,$type)
    {
        if ($type == 'info') {
            for($k = 1;$k<6;$k++){
                if ($ret['img'. $k]) {
                    $ret['banner'][] = R($ret['img' . $k],false);
                }
                unset($ret['img'.$k]);

            }
            $ret['detail'] = makeJsContent($ret['detail']);
        } else if ('list'){
            foreach ($ret as $k=>$v){
//                $ret[$k]['img'] = R($v['img'],false);
//                $ret[$k]['img_list'] = R($v['img_list'],false);
                $ret[$k]['img1'] = R($v['img1'],false);
            }
        }
        return $ret;
    }

    /**
     * @dec 获取商品分类列表
     */
    public function getClassify($request){
        $ret = $this->goods_classify->getClassify($request);
        return $ret;
    }

    public function getHomeClassify($request){
        $ret = $this->goods_classify->getHomeClassify($request);
        return $ret;
    }

    public function getRecommendGoods($request)
    {
        $ret = $this->goods->getRecommendGoods($request);
        return $ret;
    }

    public function sortGoodsList($request)
    {
        $ret = $this->goods->sortGoodsList($request);
        return $ret;
    }

    public function getSearchList($request)
    {
        $ret = $this->goods->getSearchList($request);
        return $ret;
    }

    public function getSearchName($request)
    {
        $ret = $this->goods->getSearchName($request);
        return $ret;
    }
}