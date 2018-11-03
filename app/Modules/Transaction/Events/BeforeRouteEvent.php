<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 19:21
 */


namespace App\Modules\Transaction\Events;

class BeforeRouteEvent {

    public $request ;

    public function __construct($request)
    {
        $this->request = $request;
    }



}