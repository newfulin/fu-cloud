<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class CommMailTem extends Model
{

    protected $table = "comm_mail_tem";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}