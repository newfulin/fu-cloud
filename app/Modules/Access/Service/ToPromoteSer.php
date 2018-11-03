<?php
namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Transaction\Repository\CommUserInfoRepository;

class ToPromoteSer extends Service
{
    public function getRules()
    {

    }
    protected $user;
    public function __construct(CommUserInfoRepository $user)
    {
        $this->user = $user;
    }
    // 获取分享信息
    public function wxShareInfo($request)
    {
        return 'wxShareInfo';
    }
}