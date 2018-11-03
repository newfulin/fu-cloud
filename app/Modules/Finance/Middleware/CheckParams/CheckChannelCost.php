<?php
namespace App\Modules\Finance\Middleware\CheckParams;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Common\Contracts\Middleware;
use Illuminate\Support\Facades\Config;
use App\Modules\Finance\Repository\PospChannelInfoRepository;
use App\Modules\Finance\Repository\PospChannelRateRepository;
use App\Modules\Finance\Repository\PospChannelMercInfoRepository;

/**
 * 通道资费检查
 */
class CheckChannelCost extends Middleware
{

    public $channelInfoRep;
    public $channelRateRep;
    public $channelMercInfoRep;

    /**
     * 注入Repository
     */
    public function  __construct(PospChannelInfoRepository $channelInfoRep,PospChannelRateRepository $channelRateRep,
                                PospChannelMercInfoRepository $channelMercInfoRep ){
         $this->channelInfoRep = $channelInfoRep;
         $this->channelRateRep = $channelRateRep;
         $this->channelMercInfoRep = $channelMercInfoRep;
    }

    public function handle($request, Closure $next)
    {
        Log::info("通道资费检查");
        Log::debug("ChannelCost.handle...");
        $order = $request['order'];
        $channelMerc = $this->channelMercInfoRep->getChannelMerc($order['channel_merc_id'],$order['channel_id'],$order['business_code']);
        //通道商户信息
        if(!$channelMerc){
            Err('CHANNEL_MERC_NO_EXIST');
        }
        $request['channelMerc']=$channelMerc;
        //通道资费信息
        $channelRate = $this->channelRateRep->getChannelRateById($channelMerc['rate_id']);
        //计算通道成本
        $costAmt = Money()->getChannelCost($channelRate, $order['trans_amt']);
        $request['channelRate']=$channelRate;
        $request['channelCost']['trans_amt']=$order['trans_amt'];
        $request['channelCost']['cost_amt']=$costAmt;
        Log::info(json_encode($request['channelCost']));
        return $next($request);
    }

}