<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:05
 */

return  [

    'demo' =>'123',
    'demo1'=>[
      'key1' =>'1234'
    ],


    'middle' =>[
        \App\Modules\Callback\Middleware\ThreeMiddle::class,
        \App\Modules\Callback\Middleware\FourMiddle::class
    ]


];