<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;


class ShareStat extends Model
{

    protected $table = "share_stat";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}