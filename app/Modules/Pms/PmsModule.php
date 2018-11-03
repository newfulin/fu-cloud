<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 8:56
 */

namespace App\Modules\Pms;


use App\Common\Contracts\Module;
use App\Modules\Pms\Events\InviteCodeAfterEvent;
use App\Modules\Pms\Events\ModifyTeamAfterEvent;
use App\Modules\Pms\Listener\InviteCodeListener;
use App\Modules\Pms\Listener\ModifyTeamListener;

class PmsModule extends Module
{
    public function getListen()
    {
        return [
            ModifyTeamAfterEvent::class => [
                ModifyTeamListener::class
            ],
            //邀请码生成
            InviteCodeAfterEvent::class => [
                InviteCodeListener::class
            ]

        ];

    }

    public function getSubscribe()
    {
        // TODO: Implement getSubscribe() method.
    }
}