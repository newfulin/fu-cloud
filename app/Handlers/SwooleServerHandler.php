<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 09:40
 */
namespace App\Handlers;

use Illuminate\Support\Facades\Log;

class SwooleServerHandler {

    public $fds = [];

    public function onStart(\swoole_server $serv)
    {

    }
    public function onConnect(\swoole_server $serv, $fd, $reactorId)
    {
        $this->fds[$fd] = $fd;
        $serv->send( $fd, "Hello {$fd}!" );
    }

    public function onReceive(\swoole_server $serv,$fd, $from_id, $data )
    {
        echo $data;
        foreach($this->fds as $v){
            $serv->send( $v, "Get Message From Client {$fd}:{$data}\n" );
        }
        Log::info($data);
    }
    public function onClose(\swoole_server $serv ,$fd ,$from_id)
    {
        $serv->send($fd ,  "Client {$fd} close connection\n") ;
    }





}