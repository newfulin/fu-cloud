<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;

    public $request;
    public function __construct($request)
    {
        $this->request = $request;

    }


}
