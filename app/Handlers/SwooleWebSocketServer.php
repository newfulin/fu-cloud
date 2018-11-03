<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 12:34
 */
namespace App\Handlers;

use App\Modules\Access\Service\CafeService;

class SwooleWebSocketServer  {
    public $serv;

    //onHandShake事件回调是可选的
    public function onHandShake(\swoole_http_request $request, \swoole_http_response $response)
    {
        // onHandShake函数必须返回true表示握手成功，返回其他值表示握手失败

        // print_r( $request->header );
        // if (如果不满足我某些自定义的需求条件，那么返回end输出，返回false，握手失败) {
        //    $response->end();
        //     return false;
        // }

        // websocket握手连接算法验证
        $secWebSocketKey = $request->header['sec-websocket-key'];
        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->end();
            return false;
        }
        echo $request->header['sec-websocket-key'];
        $key = base64_encode(sha1(
            $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
            true
        ));

        $headers = [
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Accept' => $key,
            'Sec-WebSocket-Version' => '13',
        ];

        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();
        echo "connected!" . PHP_EOL;
        return true;


    }
    

    public function onOpen(\swoole_websocket_server $serv,\swoole_http_request $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
//        $data = [
//            'type' => '0000',
//            'status' => '00',
//        ];
//        $serv->push($request->fd,json_encode($data));
    }

    public function onMessage(\swoole_websocket_server $serv,\swoole_websocket_frame $frame)
    {

//        $cmd  = json_decode($frame->data)->command;  //S0102;
//        app()->make($cmd)->handle($serv,json_decode($frame->data));


//        $serv->push($frame->fd,'测试测试');
        //$frame->fd，客户端的socket id，使用$server->push推送数据时需要用到
        //$frame->data，数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
        //$frame->opcode，WebSocket的OpCode类型，可以参考WebSocket协议标准文档
        //$frame->finish， 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送
        $obj = json_decode($frame->data);
        $obj->fd = $frame->fd;
        $res = app()->make(CafeService::class)->checkCodeStatus($obj);

        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $data = [
            'code' => '0000',
            'message' => '推送成功',
            'data' => json_encode($res),
        ];
        $serv->push($res['fd'], json_encode($data));
    }

    public function onClose(\swoole_websocket_server $serv,$fd)
    {
    //        $serv->push(1,"zheli 已经管理") ;
        echo "client {$fd} closed\n";
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {

        $input = $request->post;
        $order_id = $input['order_id'];
        $status = $input['status'];
        $type = $input['type'];
        $fd = app()->make(CafeService::class)->getFd($order_id);
            $this->serv->push($fd,json_encode([
                'order_id'=> $order_id,
                'status' => $status,
                'type' => $type,
            ]));
    //
    //
    //        $ret = [
    //            'code' =>'0000',
    //            'message' =>'asdf',
    //            'data' =>[]
    //        ];
    //返给侦听器listener


//                $this->serv->push($fd,json_encode(['order_id'=>$orderId]));

        $response->write(json_encode($status));
    }
}