<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 08:45
 */

namespace App\Common\Contracts;


use Closure;

abstract class Middleware {

    abstract public function handle($request , Closure $next);

}