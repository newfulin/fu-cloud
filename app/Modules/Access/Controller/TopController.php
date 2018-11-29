<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/24
 * Time: 15:26
 */
namespace App\Modules\Access\Controller;
use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class TopController extends Controller{

    public function getRules()
    {
        return [
            'getTopList' =>[
                'status' => '',
                'top_type' => '',
                'user_id' => '',
                'page' => 'required',
                'pageSize' => 'required',
            ],
            'getTopInfo' =>[
                'id' => 'required',
                'user_id' => '',
            ],
            'getTopCarInfo' =>[
                'id' => 'required',
            ]
        ];
    }

    /**
     * @desc 获取头条列表
     * @param Request $request
     * @return string
     */
    public function getTopList(Request $request)
    {
        return Access::service('TopService')
            ->with('type',$request->input('top_type'))
            ->with('user_id',$request->input('user_id'))
            ->with('status',$request->input('status'))
            ->with('page', $request->input('page'))
            ->with('pageSize', $request->input('pageSize'))
            ->run('getTopList');
    }

    /**
     * @desc 获取头条详情
     * @param Request $request
     * @return mixed
     */

    public function getTopInfo(Request $request)
    {
        return Access::service('TopService')
            ->with('id',$request->input('id'))
            ->with('user_id',$request->input('user_id'))
            ->run('getTopInfo');
    }

    /**
     * @desc 获取首页头条
     */
    public function getHomeTopList(Request $request){
        return Access::service('TopService')
            ->run('getHomeTopList');
    }

    public function getTopImg(Request $request)
    {
        return Access::service('TopService')
            ->run('getTopImg');
    }

}