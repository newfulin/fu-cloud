<?php
namespace App\Modules\Access\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRelatedController extends Controller
{
    public function getRules()
    {
        return [
            'createRecAddress' => [
                'name' => 'required',
                'tel' => 'required|mobile',
                'area' => 'required',
                'address' => 'required',
                'default' => 'required',
            ],
            'updRecAddress' => [
                'name' => '',
                'tel' => '|mobile',
                'area' => '',
                'address' => '',
                'default' => '',
                'id' => 'required'
            ],
            'chooseRecAddress' => [
                'page'  => 'required',
                'pageSize' => 'required'
            ],
            'delRecAddress' => [
                'id' => 'required',
            ],
            'setRecDefault' => [
                'id' => 'required',
            ],
            'getRecInfo' => [
                'id' => 'required'
            ],
            'getRecAddressList' => [

            ],
        ];
    }
    /**
     * @desc 获取收货地址列表
     */
    public function getRecAddressList(Request $request)
    {
        $userId = $request->user()->claims->getId();
        return Access::service('UserRelatedService')
            ->with('userId',$userId)
            ->run('getRecAddressList');
    }
    /**
     * @desc 添加收货地址
     * @param Request $request
     * @return mixed
     */
    public function createRecAddress(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        Log::info("添加收货地址:|" . $user_id);

        $re = Access::service('UserRelatedService')
            ->with('id',ID())
            ->with('user_id',$user_id)
            ->with('name',$request->input('name'))
            ->with('tel',$request->input('tel'))
            ->with('area',$request->input('area'))
            ->with('address',$request->input('address'))
            ->with('default',$request->input('default'))
            ->with('status','01')
            ->run('createRecAddress');
        return $re;
    }

    /**
     * @desc 选择收货地址
     * @param Request $request
     */
    public function chooseRecAddress(Request $request)
    {
        $user_id = $request->user()->claims->getId();
        $re = Access::service('UserRelatedService')
            ->with('user_id',$user_id)
            ->with('page',$request->input('page'))
            ->with('pageSize',$request->input('pageSize'))
            ->run('chooseRecAddress');
        return $re;
    }

    /**
     * @desc 删除收货地址
     * @param Request $request
     * @return mixed
     */
    public function delRecAddress(Request $request)
    {
        $re = Access::service('UserRelatedService')
            ->with('id',$request->input('id'))
            ->run('delRecAddress');
        return $re;
    }

    /**
     * @desc 修改收货地址
     * @param Request $request
     */
    public function updRecAddress(Request $request)
    {
        $user_id = $request->user()->claims->getId();

        $re = Access::service('UserRelatedService')
            ->with('id',$request->input('id'))
            ->with('name',$request->input('name'))
            ->with('tel',$request->input('tel'))
            ->with('area',$request->input('area'))
            ->with('address',$request->input('address'))
            ->with('default',$request->input('default'))
            ->with('id',$request->input('id'))
            ->with('user_id',$user_id)
            ->run('updRecAddress');
        return $re;
    }

    /**
     * @desc 设为默认地址
     * @param Request $request
     * @return mixed
     */
    public function setRecDefault(Request $request)
    {
        $userId = $request->user()->claims->getId();
        $re = Access::service('UserRelatedService')
            ->with('user_id',$userId)
            ->with('id',$request->input('id'))
            ->run('setRecDefault');
        return $re;
    }

    /**
     * @desc 获取收货地址信息
     * @param Request $request
     * @return mixed
     */
    public function getRecInfo(Request $request)
    {
        $re = Access::service('UserRelatedService')
            ->with('id',$request->input('id'))
            ->run('getRecInfo');
        return $re;
    }

    /**
     * @desc 获取默认收货地址
     * @param Request $request
     * @return mixed
     */
    public function getDefAddress(Request $request)
    {
        $userId = $request->user()->claims->getId();
        $re = Access::service('UserRelatedService')
            ->with('userId',$userId)
            ->run('getDefAddress');
        return $re;
    }


}