<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/5
 * Time: 11:34
 */
namespace App\Common\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class TeamRelation extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = "team_relation";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    public function getCreateTimeAttribute()
    {
        $value = $this->attributes['create_time'];

        return date('Y-m-d',strtotime($value));
    }

    public function getLoginNameAttribute()
    {
        $value = $this->attributes['login_name'];

        return substr($value, 0, 5).'****'.substr($value, 9);
    }
}
