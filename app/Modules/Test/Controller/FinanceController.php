<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 9:12
 */

namespace App\Modules\Test\Controller;


use App\Common\Contracts\Controller;

use App\Modules\Finance\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FinanceController extends Controller
{

    public function getRules()
    {
        return ['index'=>[
            'code'=>'required'
        ],'cashier'=>[
            'code'=>'required',
            'orderId'=>'min:15|max:19|required',
            'key'=>'required',
            'transAmount'=>'required'
        ],'negativePosting'=>[
            'code'=>'required',
            'batchId'=>'min:15|max:19|required',
            'key'=>'required'
        ]
        ];
    }

    /**
     * 财务记账调用
     * @desc 财务记账调用
     */
    public function cashier(Request $request)
    {
        Log::debug("cashier 财务记账接口...");
        Log::debug("财务请求码:".$request->code);
        if($request->key != "mall".date("Ymd")){
            Err("认证密钥错误:1111");
        }
        $code=$request->code;
        return Finance::service('CashierService')
            ->with('code',$code) // 财务请求码
            ->with('orderId',$request->orderId)//cash_order  id ;
            ->with('transAmount',$request->transAmount) // 交易金额 ;
            ->run();
    }

    /**
     * 财务记账调用
     * @desc 财务记账调用
     */
    public function negativePosting(Request $request)
    {
        Log::debug("cashier [反记账]   财务记账接口...");
        Log::debug("财务请求码:".$request->code);
        if($request->key != "mall".date("Ymd")){
            Err("认证密钥错误:XXXX");
        }
        $code=$request->code;
        return Finance::service('NegativePostingService')
            ->with('reqCode',$code) // 财务请求码
            ->with('batchId',$request->batchId)//cash_order  id ;
            ->run();
    }



}