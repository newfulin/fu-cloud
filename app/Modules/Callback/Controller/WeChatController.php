<?php

namespace App\Modules\Callback\Controller;

use App\Common\Contracts\Controller;
use App\Modules\Callback\Callback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatController extends Controller
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
        return [
            'NativePay' => [
                'xml' => ''
            ]
        ];
    }
    /**
     * @desc 微信回调接口 升级,充值,消费
     */
    public function NativePay(Request $request)
    {
        Log::info('微信支付订单回调开始');
        $xml = file_get_contents('php://input','r');
//        $xml = $request->input('xml');
        Log::info('----------XML--------------'.$xml);
        $data = $this->xmlToData($xml);

        $data['time'] = date("Y-m-d H:i:s");
        Log::info('------------------------交易结束时间'.$data['time']);
        Callback::service('WeChatService')
            ->with('data', $data)
            ->run('nativePay');
        return $this->replyNotify();
    }

    /**
     * 接收通知成功后应答输出XML数据
     * @param string $xml
     */
    public function replyNotify(){
        $data['return_code'] = 'SUCCESS';
        $data['return_msg'] = 'OK';
        Log::info('返回微信通知结果====='.json_encode($data).'=============END');
        $xml = $this->dataToXml( $data );
        echo $xml;
    }
    /**
     * 输出xml字符
     * @param   $params     参数名称
     * return   string      返回组装的xml
     **/
    public function dataToXml($params){
        if(!is_array($params)|| count($params) <= 0)
        {
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xmlToData($xml)
    {
        if (!$xml) {
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }
}