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


class BookkeepingUpdateController extends Controller
{

    public function getRules()
    {
        return ['bookingkupdate'=>[
            'reqCode'=>'required',
            'key'=>'required',
            'batchId'=>'required|min:15|max:19'
        ]
        ];
    }

    /**
     * 财务更新账户余额服务函数
     * @desc 财务更新账户余额服务
     */
    public function bookingkupdate(Request $request)
    {
        log::debug("bookingkupdate 账单更新处理服务接口...");
        log::debug("财务请求码:".$request->reqCode);
        if($request->key != "mall".date("Ymd")){
            Err("认证密钥错误:1111");
        }
        $code = $request->reqCode;
        $batchId = $request->batchId;
        return Finance::service('BookkeepingUpdateService')
            ->with('reqCode',$code)
            ->with('batchId',$batchId)
            ->runTransaction();
    }


}