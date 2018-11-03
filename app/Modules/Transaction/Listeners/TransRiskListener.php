<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 14:17
 */
namespace App\Modules\Transaction\Listeners ;

use App\Modules\Transaction\Events\AfterTransaction;
use App\Modules\Transaction\Events\BeforeTransaction;

class TransRiskListener {


    public function onBefore(BeforeTransaction $event)
    {
//        echo "before";
    }


    public function onAfter(AfterTransaction $event){

//        echo 'after';
    }
    
    
}