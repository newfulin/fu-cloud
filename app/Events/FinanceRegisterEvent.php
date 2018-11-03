<?php

namespace App\Events;


use Illuminate\Database\Eloquent\Model;

class FinanceRegisterEvent extends Event
{

    public $object ;


    public function __construct($object)
    {
        $this->object = $object;
    }



}