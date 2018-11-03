<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 11:09
 */
namespace App\Modules\Access ;

use App\Common\Contracts\Module;
use App\Modules\Access\Events\AnalysisEvent;
use App\Modules\Access\Events\PushMsgAfterEvent;
use App\Modules\Access\Events\SwitchTeamRelationsAfterEvent;
use App\Modules\Access\Listener\PushMsgListener;
use App\Modules\Access\Listener\AnalysisListener;
use App\Modules\Access\Events\UserRegistAfterEvent;
use App\Modules\Access\Listener\SwitchTeamRelationsListener;
use App\Modules\Access\Listener\TeamReleationListener;

class AccessModule extends Module {

    public function getListen()
    {

        return [
            UserRegistAfterEvent::class =>[
                TeamReleationListener::class
            ],
            //统计监听
            AnalysisEvent::class =>[
                AnalysisListener::class
            ],
            //消息推送
            PushMsgAfterEvent::class => [
                PushMsgListener::class
            ],
            //团队关系切换
            SwitchTeamRelationsAfterEvent::class => [
                SwitchTeamRelationsListener::class
            ]
        ];
    }

    public function getSubscribe()
    {
        // TODO: Implement getSubscribe() method.
    }


}