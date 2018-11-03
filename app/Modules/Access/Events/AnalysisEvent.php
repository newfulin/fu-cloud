<?php

namespace App\Modules\Access\Events;

use App\Events\Event;
use Illuminate\Database\Eloquent\Model;

class AnalysisEvent extends Event
{

    public $object ;

    public function __construct($object)
    {
        $this->object = $object;
    }

}