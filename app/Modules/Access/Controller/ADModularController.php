<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 14:07
 */

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ADModularController extends Controller
{
    public function getRules()
    {
        return [
            'getAdModularList' => [
                'type'   => 'required|desc:10 首页  20 咖啡厅 30 头条'
            ]
        ];
    }

    /**
     * @desc 首页 , 商城 广告模块 10 首页  20 发现
     */
    public function getAdModularList(Request $request)
    {
        Log::info('广告模块');
        return Access::service('ADModularService')
            ->with('type',$request->input('type'))
            ->run('getAdModularList');
    }

}