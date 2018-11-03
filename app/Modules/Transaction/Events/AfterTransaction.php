<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 11:48
 */
namespace App\Modules\Transaction\Events ;

class AfterTransaction {

    public $response;

    public function __construct($response)
    {
        $this->response = $response;
    }


}