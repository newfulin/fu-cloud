<?php
namespace App\Common\Models;
use Illuminate\Database\Eloquent\Model;

class ReceiveAddress extends Model
{

    protected $table = 'receive_address';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}