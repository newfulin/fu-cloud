<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 9:25
 */

namespace App\Modules\Access\Controller;


use App\Common\Contracts\Controller;
use App\Modules\Access\Access;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getRules(){
        return [
            'getCoordinate' => [
                'address' => 'required'
            ],
            'getPosition' => [
                'ing' => 'required',
                'lat'  => 'required',
                'user_id' => '',
            ],
            'getDistance' => [
                'origins' => 'required',
                'destinations' => 'required'
            ],
            'calDistance' => [
                'origins' => 'required|desc:起点,纬度,经度',
                'destinations' => 'required|desc:终点,纬度,经度'
            ]

        ];
    }

    /**
     * @desc 获取坐标
     */
    public function getCoordinate(Request $request)
    {
        return Access::service('LocationService')
            ->with('address',$request->input('address'))
            ->run('getCoordinate');
    }

    /**
     * @desc 获取定位信息
     * @param Request $request
     * @return mixed
     */
    public function getPosition(Request $request)
    {
        $request->setTrustedProxies(array('172.16.50.22'));
        return Access::service('LocationService')
            ->with('ing',$request->input('ing'))
            ->with('lat',$request->input('lat'))
            ->with('user_id',$request->input('user_id'))
            ->with('ip',$request->getClientIp())
            ->run('getPosition');
    }

    /**
     * @desc 获取距离
     * @param Request $request
     * @return mixed
     */
    public function getDistance(Request $request)
    {
        return Access::service('LocationService')
            ->with('origins', $request->input('origins'))
            ->with('destinations', $request->input('destinations'))
            ->run('getDistance');
    }

    /**
     * @desc 计算两点之间直线距离
     * @return mixed
     */
    public function calDistance(Request $request)
    {
        return Access::service('LocationService')
            ->with('origins', $request->input('origins'))
            ->with('destinations', $request->input('destinations'))
            ->run('calDistance');
    }

}