<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/2
 * Time: 13:07
 */
namespace App\Modules\Finance\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Finance\Repository\AccountRepository;
use Closure;

class UtilDictMiddle extends Middleware{
    /**
     * @desc 对返回的结果做中文注解切换
    */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $ret = $this->translate($response['dict'],$response['list']);

        return $ret;
    }

    public function translate($dict,$dataSet)
    {
        if(!$dataSet){
            return [];
        }
        $result = [];
        foreach($dataSet as $key => $value){
            if(is_array($value))
            {
                $result[$key] = $this->getArrayDict($dict,$value);
            }else if(is_object($value)){
                $result[$key] = $this->getArrayDict($dict,get_object_vars($value));
            }else{
                $result[$key] = $this->getDict($dict,$key,$value);
            }
        }
        return $result;
    }

    public function getArrayDict($dict,$dataSet)
    {
        $result = [];
        foreach($dataSet as $key => $value){
            if(is_array($value))
            {
                $result[$key] = $this->getArrayDict($dict,$value);
            }else{
                $result[$key] = $this->getDict($dict,$key,$value);
            }
        }
        return $result;
    }

    public function getDict($dict,$key,$value)
    {
        $rules = config($dict);
        if(!$rules){
            return $value;
        };
        if(isset($rules[$key]) && is_array($rules[$key])){
            foreach($rules[$key] as $k=>$v){
                if($k == $value){
                    return $v;
                }
            }
        }else if(isset($rules[$key])){
            return $rules[$key];
        }else{
            return $value;
        }
    }


}
