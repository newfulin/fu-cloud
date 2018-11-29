<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 9:12
 */

namespace App\Modules\Test\Controller;


use App\Common\Contracts\Controller;

use App\Common\Jwt\Token;
use App\Modules\Callback\Callback;
use App\Modules\Test\Test;
use App\Modules\Test\Events\DemoAfterEvent;
use App\Modules\Test\Entity\Entity;
use App\Modules\Test\Service\TestService;
use App\Modules\Test\TestModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class DemoController extends Controller
{
    public function getRules()
    {
        return [
            'testEvent' => [],
            'getToken' => [
                'user_id' => 'required|desc:1090384899975892480'
//                'accountNo'         => 'required|accountno',  //银行卡账号
            ]
        ];
    }

    public function getToken(Request $request,Token $token){
//        $id = substr(ID(),11,8);
//        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//        $str = "";
//        for ($i = 0; $i < 2; $i++) {
//            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
//        }
//        $re = $str.$id;
//        return $re;
//        $id = substr(ID(),11,8);
//        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
//        $str = "";
//        for ($i = 0; $i < 2; $i++) {
//            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
//        }
//        $re = $str.$id;
//        return $re;
//        $ip_long = array(
//            array('607649792', '608174079'), //36.56.0.0-36.63.255.255
//            array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
//            array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
//            array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
//            array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
//            array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
//            array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
//            array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
//            array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
//            array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
//        );
//        $rand_key = mt_rand(0, 9);
//        return long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));


        return $token->setId($request->input('user_id'))
            ->setName('13345056189')
            ->setRole('P1501')
            ->getToken();
    }

    public function testEvent(Request $request){
        $entity = new Entity();
        $entity->setBatchId(rand(1111,9999));
        $entity->setReqCode("T1234");
        Event::fire(new DemoAfterEvent($entity));
        return 'suc';
    }

    public function testAfter(){
        return Test::service('TestService')
            ->run('testAfter');
    }

    public function testFun(){
        $param = [
            'user_id' => '1138486007155620352',
            'recommend_id' => '1131592936878881792',
        ];
        return app('nxp-team')->query()
            ->switchTeamRelations($param);

        $func = 'A0110';

        $data = [
            'business_code' => $func,
            'user_id' => '1090395121469389312'
        ];

        return Callback::service($func)
            ->with('data',$data)
            ->run();
    }

    public function retTest(){
        $params = [];

        return  Callback::service('A0600')
            ->with('detailId','1150788208565513472')
            ->with('params',$params)
            ->run();
    }


}