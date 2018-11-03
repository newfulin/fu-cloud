<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 9:31
 */

namespace App\Modules\Access\Service;


use App\Common\Contracts\Service;
use App\Modules\Access\Repository\MapLocationRepo;
use Illuminate\Support\Facades\Log;

class LocationService extends Service
{
    public function getRules(){

    }

    protected $repo;
    public function __construct(MapLocationRepo $repo)
    {
        $this->repo = $repo;
    }

    public function getPosition($request)
    {
        Log::info('地址定位'.json_encode($request));
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $ak = config('parameter.BD.ak');
        //以Geocoding服务为例，地理编码的请求url，参数待填
        $url = config('parameter.BD.position');
        //地理编码的请求中address参数
        // $location = implode(',', [39.934, 116.329]);
        $location = $request['lat'] . ',' . $request['ing'];
        //地理编码的请求output参数
        $output = 'json';
        $coordtype = 'wgs84ll';
        $target = sprintf($url, $location, $coordtype, $output, $ak);

        $re = $this->request_by_curl($target);
        Log::info('-----解析结果------' . $re);

        $re = json_decode($re, true);return $re;
        $now_time = date('Y-m-d');
        $now_district = $re['result']['addressComponent']['district'];
        $re_check = $this->repo->checkLocation($request['ip']);
        Log::info(json_encode($re_check));
        $check_time = strtotime($re_check['create_time']);
        Log::info('$now_district==='.$now_district.'|$now_time=='.$now_time.'|$user_id=='.$request['user_id']);
        if ($re_check['district'] == $now_district && $now_time == date('Y-m-d',$check_time) && $re_check['user_id'] == $request['user_id'])
        {
            return $re;
        }

        $params = array(
            'address' => $re['result']['formatted_address'],
            'province' => $re['result']['addressComponent']['province'],
            'city' => $re['result']['addressComponent']['city'],
            'district' => $now_district,
            'ip' => 'localhost',
            'user_id' => $request['user_id'],
            'login' => '01'
        );

        if ($request['user_id'] != '') {
            $params['login'] = '02';
            Log::info($request['user_id']);

        }
        Log::info('参数-----------------------------'.json_encode($params));

        $this->repo->insert($params);
        return $re;
    }

    public function getCoordinate($request)
    {
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $ak = config('parameter.BD.ak');
        //以Geocoding服务为例，地理编码的请求url，参数待填
        $url = config('parameter.BD.coordinate');
        //地理编码的请求中address参数
        // $location = implode(',', [39.934, 116.329]);
        $address = $request['address'];
        Log::info('-----address------' . $address);
        //地理编码的请求output参数
        $output = 'json';

        $target = sprintf($url, $address, $output, $ak);
        $re = $this->request_by_curl($target);
        Log::info('-----解析结果------' . $re);
        $re = json_decode($re, true);
        return $re;
    }
    public function getDistance($request)
    {
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $ak = config('parameter.BD.ak');
        //请求url，参数待填
        $url = config('parameter.BD.distance');
        $origins = $request['origins'];
        $destinations = $request['destinations'];
        $coord_type = 'wgs84';
        $tactics = '13';
        $target = sprintf($url, $origins, $destinations, $coord_type, $tactics, $ak);
        $re = $this->request_by_curl($target);
        Log::info('--------------------URL解析------------'.$re);
        $re = json_decode($re, true);
        return $re;
    }

    public function request_by_curl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }

    public function calDistance($request)
    {
        $origins = $request['origins'];
        $destinations = $request['destinations'];
        $orPoint = strpos($origins,',');
        $lat1 = substr($origins,0,$orPoint);
        $lng1 = substr($origins,$orPoint+1);
        $dePoint = strpos($destinations,',');
        $lat2 = substr($destinations,0,$dePoint);
        $lng2 = substr($destinations,$dePoint+1);

        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        $rice = round($calculatedDistance);

        if($rice > 1000){
            $kil = ($rice / 1000);
            return round( $kil, 1, PHP_ROUND_HALF_UP).' 公里';
        }else{

            return $rice.' 米';
        }
    }
}