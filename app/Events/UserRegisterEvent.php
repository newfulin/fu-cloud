<?php

namespace App\Events;


use Illuminate\Database\Eloquent\Model;

class UserRegisterEvent extends Event
{

    public $object ;


    public function __construct(Model $object)
    {
        $this->object = $object;
    }



}