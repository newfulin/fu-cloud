<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 08:35
 */
namespace App\Console\Commands;

use App\Handlers\SwooleServerHandler;
use App\Handlers\SwooleTimerHandler;
use App\Handlers\SwooleWebSocketServer;
use Illuminate\Console\Command;

class SwooleCommand extends Command {

    protected $signature = 'swoole:server {action}';
    protected $description = 'Swoole Server Command';


    public function handle()
    {
        switch ($this->argument('action')){
            case 'start' :
                $this->startSwooleServer();
                break;
            case 'timer' :
                $this->startTimeServer();
                break;
            case 'websocket' :
                $this->startWebSocketServer();
                break;
            default :
                break;
        }

    }

    protected function startSwooleServer()
    {

        $this->info('swoole server start');
        $serv = new \swoole_server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $serv->set(array(
            'worker_num' => 4,
            'daemonize' => false,
            'backlog' => 128,
        ));

        $handler = app()->make(SwooleServerHandler::class);
        $serv->on('Start',[$handler,'onStart']);
        $serv->on('Connect', [$handler,'onConnect']);
        $serv->on('Receive', [$handler,'onReceive']);
        $serv->on('Close',   [$handler,'onClose']);
        $serv->start();

//        $serv->manager_pid;  //管理进程的PID，通过向管理进程发送SIGUSR1信号可实现柔性重启
//        $serv->master_pid;  //主进程的PID，通过向主进程发送SIGTERM信号可安全关闭服务器
//        $serv->connections; //当前服务器的客户端连接，可使用foreach遍历所有连接
    }



    protected function startTimeServer(){

        $period = 2000;
        $handler = app()->make(SwooleTimerHandler::class);
        swoole_timer_tick($period,[$handler,'onTimer']);
    }


    protected function startWebSocketServer(){

        $serv = new \swoole_websocket_server("0.0.0.0", 9502);
        $handle = app()->make(SwooleWebSocketServer::class);
        $handle->serv = $serv;
        $serv->on('open',[$handle,'onOpen']);
        $serv->on('message',[$handle,'onMessage']);
        $serv->on('close',[$handle,'onClose']);
        $serv->on('request',[$handle,'onRequest']);
        $serv->start();


    }

}