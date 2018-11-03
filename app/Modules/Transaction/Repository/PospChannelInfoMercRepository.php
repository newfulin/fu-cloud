<?php
namespace App\Modules\Transaction\Repository;

use App\Common\Contracts\Repository;
use App\Common\Models\PospChannelMercInfo;
use Illuminate\Support\Facades\DB;


class PospChannelInfoMercRepository extends Repository
{
    public function __construct(PospChannelMercInfo $model)
    {
        $this->model = $model;
    }
    public function getChannelInfo($merc_type)
    {

        $re = optional(DB::table('posp_channel_merc_info as t0')
            ->select(
                        't0.merc_id','t0.merc_name','t0.id'
//                        ,'t0.merc_type'
                        ,'t0.channel_id'
//                        ,'t0.rate_id'
//                        ,'t0.status as merc_status'
//                        ,'t0.tranbegin_time as merc_begin'
//                        ,'t0.tranend_time as merc_end'
//                        ,'t1.bean_name','t1.priority','t1.limit_amount'
//                        ,'t1.status as channel_status'
//                        ,'t1.tranbegin_time as channel_begin'
//                        ,'t1.tranend_time as channel_end'
//                        ,'t1.channel_type','t1.request_url'
//                        ,'t1.secret_key','t1.channel_weight'
//                        ,'t2.cost_rate','t2.cost_max_rate','t2.norm_rate','t2.norm_max_rate'
//                        ,'t2.advance_rate','t2.advance_max_rate'
                    )
            ->leftJoin('posp_channel_info as t1',function ($join){
                $join->on('t0.channel_id','=','t1.channel_id');
            })
//            ->leftJoin('posp_channel_rate as t2',function ($join){
//                $join->on('t0.rate_id','=','t2.id');
//            })
            ->where('t0.merc_type',$merc_type)
            ->where('t0.status','10')
//            ->where('t1.status',1)
            ->get())
            ->toArray();
        return $re;


    }

}