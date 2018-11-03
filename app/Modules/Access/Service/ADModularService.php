<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 14:08
 */

namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Handlers\SwooleWebSocketServer;
use App\Modules\Access\Repository\ImgHomeRepo;

class ADModularService extends Service
{
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @desc 广告模块
     */
    public function getAdModularList(ImgHomeRepo $repo,$request)
    {
        return $repo->getAdModularHomeList($request);
    }
}