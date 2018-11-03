<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 10:00
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class ImgService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getImage($request)
    {
        //图片
        $path = $request['img_path'];
        //距右边距
        $right = $request['right_edge'];
        //距下边距
        $bottom = $request['bottom_edge'];
        //用户ID
        $user_id = $request['user_id'];
        //别名 CXB SIXCAR
        $alias = $request['alias'];
        //图片ID
        $img_id = $request['img_id'];

        $img_name = $alias.'_'.$user_id.'_'.$img_id;

        if(empty($request['user_id']) || !isset($request['user_id'])){
            $img_name = $alias.'_'.$img_id;
        }

        // 记录开始时间
        $startTimestamp = microtime(true);

        if(empty($path) || $right == 0 || $bottom == 0){
            return $path;
        }

        $time = date('Y-m-d');
        $dir = 'Data/upload/'.$time . '/'; //保存路径

        $img_path = $dir.$img_name.'.jpg';

        //判断文件手否存在在
        if(file_exists($img_path)){
            return R(substr($img_path,5));
        }

        //检查文件是否创建
        $this->checkFile($dir);

        $client = new \GuzzleHttp\Client;

        $avatarResponse = $client->get($path);

        $img = Image::make($avatarResponse->getBody()->getContents());

        $qrcode = $this->getQRCode($user_id,$alias,$img_id);

        $qr = Image::make($qrcode)->resize(200, 200);

        $img->insert($qr, 'bottom-right', $right, $bottom);

        $img->save($img_path);

        //销毁
        $img->destroy();

        // 记录结束时间
        $endTimestamp = microtime(true);


        if(!empty($request['user_id']) || isset($request['user_id'])){
            unlink($qrcode);
        }

        return R(substr($img_path,5));
    }

    public function getQRCode($user_id,$alias = '',$img_id,$width = 250)
    {
        if(empty($user_id)){
            $public = config('const_share.MERGE.'.$alias.'.public');
            return R($public);
        }

        $time = date('Y-m-d');
        $url = config('const_share.MERGE.'.$alias.'.register_url').'recommend_id='.$user_id.'&imgId='.$img_id;

        $logo = config('const_share.MERGE.'.$alias.'.logo');

        $dir = 'Data/upload/'.$time . '/qccode_'.rand(1000,9999).'.png';

        QrCode::format('png')->size($width)->merge($logo,.22)->margin(0)->generate($url,$dir); //保存路径);
        return $dir;
    }

    //检查文件是否创建
    public function checkFile($dir)
    {
        if (!file_exists($dir)) {
            @mkdir($dir,0777,true);
        }
    }


}