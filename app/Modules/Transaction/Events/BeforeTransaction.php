<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 11:43
 */

namespace App\Modules\Transaction\Events;

class BeforeTransaction {

    public $request ;

    public function __construct($request)
    {
        $this->request = $request;
    }

}