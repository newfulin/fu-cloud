<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 14:44
 */

namespace App\Common\Contracts;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function __construct(Request $request)
    {
        //验证字段
        $route = $request->route();
        if($route){
            list(, $action) = explode('@', $route[1]['uses']);
            $rules = $this->getRules();
            if(isset($rules[$action]))
                $this->validate($request,$rules[$action],[],[]);
        }

    }
    abstract public function getRules();


}
