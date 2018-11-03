<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class CommFeedback extends Model
{

    protected $table = "comm_feedback";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}