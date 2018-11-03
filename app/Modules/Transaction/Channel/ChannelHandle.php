<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 09:33
 */
namespace App\Modules\Transaction\Channel ;


use App\Common\Contracts\Channel;

class ChannelHandle {

    public $channel ;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function transaction($request){
        return $this->channel->transaction($request);
    }

    public function register($request)
    {
        return $this->channel->register($request);
    }

    public function callback($request)
    {
        return $this->channel->callback($request);
    }

    public function getCash($request)
    {
        return $this->channel->getCash($request);
    }

}