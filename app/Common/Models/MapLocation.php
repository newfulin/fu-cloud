<?php
namespace App\Common\Models;
use Illuminate\Database\Eloquent\Model;

class MapLocation extends Model
{
    protected $table = "map_location";
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}