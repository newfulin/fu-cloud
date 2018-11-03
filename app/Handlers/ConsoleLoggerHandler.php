<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/7
 * Time: 11:01
 */
namespace App\Handlers ;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;


class ConsoleLoggerHandler extends StreamHandler {

    public $url;
    public $stream;


    public function __construct()
    {
        $level = Logger::DEBUG;
        $stream = storage_path('logs/cli-log-' . date('Y-m-d') . '.log');
        parent::__construct($stream, $level);
        $this->setFormatter($this->getCustomFormatter());
    }


    protected function write(array $record)
    {
        $this->setstream();
        $log = sprintf(
            "%s|",
            date('Y-m-d H:i:s')
            );
        $ret = "";
            $record = $this->processRecord($record);
            $ret .= $log . $this->getFormatter()->format($record);

        // 调用日志写入方法
        parent::write(['formatted' => $ret]);



    }

    protected function getCustomFormatter()
    {
        return new LineFormatter(
            "%level_name%|%message%\n",
            true,
            true
        );
    }


    public function setStream()
    {
        $stream = storage_path('logs/cli-log-' . date('Y-m-d') . '.log');
        if($this->stream != $stream){
            $this->close();
        }

        if (is_resource($stream)) {
            $this->stream = $stream;
        } elseif (is_string($stream)) {
            $this->url = $stream;
        } else {
            throw new \InvalidArgumentException('A stream must either be a resource or a string.');
        }

    }
}