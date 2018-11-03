<?php
namespace App\Modules\Transaction\Middleware\Trans;
use App\Common\Contracts\Middleware;
use App\Modules\Access\Repository\CommUserRepo;
use App\Modules\Finance\Repository\AcctUserTariffRateRepository;
use App\Modules\Transaction\Repository\CommUserInfoRepository;
use App\Modules\Transaction\Repository\PospChannelInfoMercRepository;
use Closure;
use Illuminate\Support\Facades\Log;

class GetRateMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        Log::info('---参数专用检测---'.json_encode($request));
        // TODO: Implement handle() method.
        $request['receive_time'] = date("Y-m-d H:i:s", $request['time']);
        $request['cash_type'] = '';
        $request['merc_type'] = '80';
        $rateInfo = app()->make(AcctUserTariffRateRepository::class)
            ->getRateInfo($request['business_code'],$request['tariff_code']);

        if(!$rateInfo){
            Err("用户资费不存在!!,5040");
        }

        $request['rateInfo'] = $rateInfo;
        Log::info('rateInfo======'.json_encode($rateInfo));

        $receive_amt = Money()->getReceiveAmt($rateInfo,$request['trans_amt']);
        $request['receive_amt'] = $receive_amt;
        Log::info('$receive_amt======'.json_encode($receive_amt));

        $userInfo = app()->make(CommUserRepo::class)->getUser($request['user_id']);
        $request['userInfo'] = $userInfo;

        Log::info('$userInfo======'.json_encode($userInfo));

        $channelInfo = app()->make(PospChannelInfoMercRepository::class)
            ->getChannelInfo($request['business_code']);

        if (!$channelInfo) {
            Err('通道商户不存在','7777');
        }
        Log::info('$channelInfo======'.json_encode($channelInfo));
        $request['channelInfo'] = $channelInfo[0];

        return $next($request);
    }
}
