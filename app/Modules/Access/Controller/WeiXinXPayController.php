<?php
/**
 * Created by PhpStorm.
 * User: Suu_L
 * Date: 2018/11/27
 * Time: 18:56
 */

namespace App\Modules\Access\Controller;
use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use App\Modules\Transaction\Trans;
class WeiXinXPayController extends Controller
{
    public function getRules(){
        // Todo something
    }

    public function requestPayment(Request $request){


        return Trans::service('ChannelTrans')
            ->with('business_code',md5("123"))
            ->with('trans_amt',md5("1223"))
            ->with('tariff_code',md5("1233"))
            ->with('user_id',md5("12223"))
            ->with('time',time())
            ->run('testPay');
    }
}