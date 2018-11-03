<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 14:42
 */

namespace App\Modules\Access\Middleware;


use App\Common\Contracts\Middleware;
use App\Common\Validation\FileUpload;
use Closure;
use Illuminate\Support\Facades\Log;

class UploadFileMiddle extends Middleware
{
    public function handle($request, Closure $next)
    {
        Log::info('上传图片 | ');
        $upload = new FileUpload();
        //设置属性(上传的位置， 大小， 类型，名是是否要随机生成)
        $upload -> set("maxsize", 10 * 1024 * 1000);
        $upload -> set("allowtype", array("gif", "png", "jpg","jpeg"));
        $upload -> set("israndname", false);
        $url = $upload -> upload('file');

        if(!$url) {
            Err('文件上传失败!');
        }

        //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
        $request['path'] = $url['path'];

        return $next($request);
    }
}