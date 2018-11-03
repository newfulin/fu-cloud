<?php

namespace App\Modules\Transaction\Service;


use App\Common\Contracts\Service;
use App\Modules\Transaction\Events\RequestFinanceEvent;
use App\Modules\Transaction\Middleware\DetailMiddleware;
use App\Modules\Transaction\Middleware\GoodsPayOrderMiddle;
use App\Modules\Transaction\Middleware\Trans\CaseTransMiddleware;
use App\Modules\Transaction\Middleware\Trans\CheckLVMiddleware;
use App\Modules\Transaction\Middleware\Trans\CheckMoneyMiddleware;
use App\Modules\Transaction\Middleware\Trans\GeneratingOrderMiddleware;
use App\Modules\Transaction\Middleware\Trans\GetRateMiddleware;
use App\Modules\Transaction\Middleware\SummaryMiddleware;
use App\Modules\Transaction\Middleware\WeChatOrderMiddle;
use App\Modules\Transaction\Middleware\WithdrawOrderMiddle;
use App\Modules\Transaction\Middleware\WxPayAppMiddleware;
use App\Modules\Transaction\Middleware\WxPayOrderMiddleware;


class ChannelTrans extends Service
{

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public $middleware = [
        CheckLVMiddleware::class
        => [
            'only' => 'PMSCoupon'
        ],
        CheckMoneyMiddleware::class
        => [
            'only' => 'customChannel',
        ],
        GeneratingOrderMiddleware::class
        => [
           ],
        GetRateMiddleware::class
        => [
//            'except' =>
        ],
        //微信下单
        WeChatOrderMiddle::class => [
            'only' => ['userUpGrade','payGoodsOrder']
        ],
        //微信订单支付
        WxPayOrderMiddleware::class
        => [
            'only' => ['userUpGrade']
        ],
        //商品订单支付
        GoodsPayOrderMiddle::class => [
            'only' => ['payGoodsOrder']
        ],
        //pms用户升级流水
        DetailMiddleware::class => [
            'only' => ['customChannel']
        ],
        WxPayAppMiddleware::class
        => [
            'only' => 'resaleCoupon',
        ],
        //提现
        CaseTransMiddleware::class
        => [
            'only' => 'withdrawals',
        ],
        //提现流水
        WithdrawOrderMiddle::class
        => [
            'only' => 'withdrawals'
        ],
        //汇总流水
        SummaryMiddleware::class
        => [
        ]
    ];

    public $beforeEvent = [

    ];
    public $afterEvent = [
        // 接口调用，直接请求财务记账
        RequestFinanceEvent::class
        => [
            'only' => ['withdrawals','transAccounts']
        ]
    ];

    // 提现
    public function withdrawals($request)
    {
        return $request;
    }

    // 用户升级
    public function userUpGrade($request)
    {
        return $request['params'];
    }

    // 商品订单支付
    public function payGoodsOrder($request)
    {
        return $request['params'];
    }

    //订单确认收货 分润
    public function orderShareProfit($request){
        return $request;
    }

    // 客户加盟
    public function customChannel($request)
    {
        $params = array(
            'code' => '0000',
            'detailId' => $request['detailParams']['id'],
            'summaryId' => $request['summaryParams']['id'],
            'msg' => '待审核'
        );
        return $params;
    }

}