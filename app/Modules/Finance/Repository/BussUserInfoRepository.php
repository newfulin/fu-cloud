<?php
/**
 * 人员管理
 */
namespace App\Modules\Finance\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\BussUserInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BussUserInfoRepository extends Repository
{
    public function __construct(BussUserInfo $model)
    {
        $this->model = $model;
    }

    /**
     * 人员信息
     */
    public function getEntity($key,$value){
        $ret = $this->model->where($key,'=',$value)->first();
        return $ret;
    }

    //查询全部数据
    public function getData()
    {
        $ret = optional($this->model
                ->get())
                ->toArray();
        return $ret;
    }

    //总监
    public function getBusinessData()
    {
        return optional(DB::table('buss_user_info')
            ->select('business_name','business_tel')
            ->distinct('business_name,business_tel')
            ->get())
            ->toArray();
    }
    //查询销售部数据
    public function getMarketData()
    {
        return optional(DB::table('buss_user_info')
                ->select('business_name','business_tel','market_name','market_tel')
                ->distinct('market_name,market_tel')
                ->get())
                ->toArray();
    }

    //查询销售部数据
    public function getMerchData()
    {
        return optional(DB::table('buss_user_info')
            ->select('market_name','market_tel','merch_name','merch_tel')
            ->distinct('merch_name,merch_tel')
            ->get())
            ->toArray();
    }
    
    //查询合伙人
    public function getPartnerData()
    {
        return optional(DB::table('buss_user_info')
            ->select('partner_name','partner_tel','business_name','business_tel','merch_name','merch_tel','market_name','market_tel')
            ->get())
            ->toArray();
    }
}