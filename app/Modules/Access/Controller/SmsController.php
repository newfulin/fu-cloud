<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 14:27
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function getRules()
    {
        return [
            'sendSms' => [
                'mobile'      => 'required|mobile',
            ]
        ];
    }

    /**
     * @desc 发送短信
     */
    public function sendSms(Request $request){
        Log::info('发送短信 |'.$request->input('mobile'));

        $param  = config('const_sms.USER_SMS_TEMPLATE');   //发送短信模版code,类型

        return Access::service('SmsService')
            ->with('mobile',$request->input('mobile'))
            ->with('param',$param)
            ->run('send');
    }

}