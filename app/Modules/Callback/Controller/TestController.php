<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:00
 */
namespace App\Modules\Callback\Controller ;

use App\Common\Contracts\Controller;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Callback\Callback;
use Illuminate\Http\Request;

class TestController extends Controller{

    public function getRules()
    {
       return [];
    }

    /**
     * @desc 回调测试
     * @param CommUserRepo $repo
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function index(CommUserRepo $repo)
    {
//        $req = [
//            'param2' =>'222222',
//            'param3' =>'333333'
//        ];
//
//        $response = Callback::service('DemoService')
//                ->with('num',0)
//                ->with('param1','1111111')
//                ->with('param4','4444444')
////                ->pass($req)
////                ->pass($request->all())
//                ->run();
//
//        return $response;



        return $repo->find("1459128570499566680");

    }
    
    
}