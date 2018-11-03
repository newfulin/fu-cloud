<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/3
 * Time: 11:50
 */

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommBankDb;

class CommBankDbRepo extends Repository
{
    public function __construct(CommBankDb $model)
    {
        $this->model = $model;
    }

    public function getAreaBankList($request)
    {
        $request['stateName'] = preg_replace('# #','',$request['stateName']);
        $headEng = strtolower($request['headEng']);
        $sql = $this->model
            ->select('id','code','name','head_eng','head_name','state_name','city_name','state_code','city_code')
            ->where('head_eng',$headEng)
            ->where('state_name','like','%'.$request['stateName'].'%');

        if(isset($request['cityName'])){
            $request['cityName'] = preg_replace('# #','',$request['cityName']);
            $sql = $sql->where('city_name','like','%'.$request['cityName'].'%');
        }

        $ret = optional(
            $sql->paginate($request['pageSize']))
            ->toArray();

        return $ret['data'];
    }
}