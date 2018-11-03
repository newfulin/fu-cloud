<?php
namespace App\Modules\Transaction\Listeners;
use App\Modules\Callback\Callback;
use Illuminate\Support\Facades\Log;

class CallBackListener
{
    public function handle($event)
    {

        Log::info('=============================================='.'微信模拟回调');
        $request = $event->request;
//        Log::info('--------------------------------------------'.json_encode($request));
        $params = array(
            'appid' => $request['params']['appid'],
            'attach' => '测试啊',
            'bank_type' => 'CFT',
            'cash_fee' => $request['WeChatParams']['total_fee'],
            'fee_type' => 'CNY',
            'is_subscribe' => 'N',
            'mch_id' => '1494494982',
            'nonce_str' => $request['params']['noncestr'],
            'openid' => 'oiicM1UCuyIyn2f2HiiOtStZfPPU',
            'out_trade_no' => $request['WeChatParams']['id'],
            'result_code' => 'SUCCESS',
            'return_code' => 'SUCCESS',
            'sign' => $request['WeChatParams']['sign'],
            'time_end' => date('YmdHis'),
            'total_fee' => $request['WeChatParams']['total_fee'],
            'trade_type' => 'NATIVE',
            'transaction_id' => '4200000073201802046645346887',
        );
        Log::info('==================='.json_encode($params));
        $xml = $this->dataToXml($params);
        Log::info('***********************'.$xml);
        Callback::service('WeChatService');
    }
    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xmlToData($xml){
        if(!$xml){
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
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
}
