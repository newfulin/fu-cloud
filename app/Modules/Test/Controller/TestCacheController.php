<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/5/30
 * Time: 09:21
 */
namespace App\Modules\Test\Controller;

use App\Common\Contracts\Controller;
use App\Common\Validation\FileUpload;
use App\Common\Validation\ImgCompress;
use App\Modules\Test\Repository\TestRepo;
use App\Modules\Test\Test;
use Illuminate\Support\Facades\Log;
use Unirest\Request;

class TestCacheController extends Controller{

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }


    public function index()
    {
        return Repo(TestRepo::class)->getTest();
    }

    public function updateTest(){
        Log::info('-------------获取上传文件数据'.json_encode($_FILES));
        $upload = new FileUpload();
        //设置属性(上传的位置， 大小， 类型，名是是否要随机生成)
        $upload -> set("maxsize", 10 * 1024 * 1000);
        $upload -> set("allowtype", array("gif", "png", "jpg","jpeg"));
        $upload -> set("israndname", false);
        $url = $upload -> upload('file');

        if(!$url) {
            Err('文件上传失败!');
        }

        ////使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
        $url['path'] = "https://".$_SERVER['HTTP_HOST'] .$url['path'];
        return $url;


//        $src = 'Data/upload/1.jpg';
//        $img = getimagesize($src);
//
//        $info = filesize($src);
//        if(($info / 1024 / 1000) > 1 ){
//            $imgsrc = $src;
//            $imgdst = $src;
//
//            list($width, $height, $type) = getimagesize($imgsrc);
//            $imgwidth = $width;
//            $imgheight = $height;
//
//            $percent = 1;  #原图压缩，不缩放
//
//
//            $size = (int)(filesize($imgdst) / 1024);
//
//            $image = new ImgCompress($imgsrc,$percent,$imgwidth);
//            $image->compressImg($imgdst);
//
//        }



    }
}