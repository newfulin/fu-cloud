<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 09:26
 */
namespace App\Common\Contracts ;

abstract class Channel {

    abstract  public function handle($request);
    public function register($request){}
    public function getCash($request){}
    public function callback($request){}



}

