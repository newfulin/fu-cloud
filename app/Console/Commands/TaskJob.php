<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/23
 * Time: 18:20
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TaskJob extends Command
{

    protected $signature = 'test_command';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $i = 1;
        while ($i<10){
            echo "$i\r\n";
            $i++;
        }

    }

}