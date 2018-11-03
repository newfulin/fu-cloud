<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/18
 * Time: 15:43
 */

namespace App\Handlers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;

class LoggerHandler extends StreamHandler
{

    public function __construct()
    {
        $level = Logger::DEBUG;
        $stream = storage_path('logs/log-' . date('Y-m-d') . '.log');
        parent::__construct($stream, $level);
        $this->setFormatter($this->getCustomFormatter());
    }


    public function handleBatch(array $records)
    {

        $request = app('request');
        $log = sprintf(
            "%s|%s|%s|%s|",
            date('Y-m-d H:i:s'),
            (microtime(true) * 10000),
            $request->getClientIp(),
            $request->path()
        );

        $ret = "";
        // 然后将内存中的日志追加到$log这个变量里
        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }
            $record = $this->processRecord($record);
            $ret .= $log . $this->getFormatter()->format($record);
        }

        // 调用日志写入方法
        $this->write(['formatted' => $ret]);


    }

    protected function getCustomFormatter()
    {
        return new LineFormatter(
            "%level_name%|%message%\n",
            true,
            true
        );
    }


}
