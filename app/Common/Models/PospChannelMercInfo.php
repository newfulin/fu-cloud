<?php
namespace App\Common\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * 通道商户
 */
class PospChannelMercInfo extends Model

{
    protected $table = "posp_channel_merc_info";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}