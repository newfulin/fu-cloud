<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/2/28
 * Time: 18:34
 */

namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Common\Models\CarBrand;
use App\Modules\Access\Access;
use App\Modules\Access\Repository\CarBrandRepo;
use App\Modules\Store\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class WidgetController extends Controller
{
    public function getRules()
    {
        return [
            'getFaseStoreList' => [
                'page' => 'required|default:1',
                'pageSize' => 'required|default:3',
                'keyWord' => '',
                'flag' => 'required'
            ],
            'getHelpType' => [
                'page' => 'required|default:1',
                'pageSize' => 'required|default:3',
                'keyWord' => '',
            ],
            'myPointBalance' => [

            ]
        ];
    }

    /**
     * @desc 获取帮助类型
     */
    public function getHelpType(Request $request)
    {
        return Access::service('WidgetService')
            ->with('page', $request->input('page'))
            ->with('pageSize', $request->input('pageSize'))
            ->with('keyWord', $request->input('keyWord'))
            ->run('getHelpType');
    }
    public function getHelpList(Request $request)
    {
        return Access::service('WidgetService')
            ->run('getHelpList');
    }


    /**
     * @desc 获取首页小部件 轮播图
     */
    public function getWidgetBanner()
    {
        return Access::service('WidgetService')->run('getWidgetBanner');
    }
}