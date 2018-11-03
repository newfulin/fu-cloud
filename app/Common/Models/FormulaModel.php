<?php
/**
 * User: satsun
 * Date: 2018/2/24
 * Time: 14:14
 */
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 配方模板表
 */
class FormulaModel extends Model {



    protected $table = "formula_model";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

}
