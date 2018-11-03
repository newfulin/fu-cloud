<?php
/**
 * 营销模式类
 * User: wangjh
 * Date: 2018/1/29
 * Time: 11:34
 */
namespace  App\Modules\Finance\Middleware\Process;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class MarkModel extends Process {

    
    public function handle($request, Closure $next)
    {
        Log::info("---------Process.MarkModel----------");
        $template  = array_shift($request['template']);
        //Log::info(json_encode($template));
        $request['book']['template'] =$template;
        $key = $template['voucher_batch_id'];
        $bookingOrders = $this->getBookingOrder($request);
        if(is_array($bookingOrders))
        foreach($bookingOrders as $key => $order){
            $request['book']['booking_order'][$key] = $order;
        }
        return $next($request);
    }
    
    
    /**
     * 营销模式 分润类库
     */
    public function getBookingOrder($request)
    {
        $policy = $request['policy'];
        $markBookingOrder = parent::getBookingOrder($request);
        $cashierBean = Config::get('finance.mark_model.'.$policy);
        Log::info("分润类库::".$cashierBean);
        return app()->make($cashierBean)->handle($markBookingOrder,$request);
    }


}